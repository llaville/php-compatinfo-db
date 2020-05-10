<?php
declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Service;

use Bartlett\CompatInfoDb\Application\Command\BuildExtensionCommand as AppBuildExtensionCommand;
use Bartlett\CompatInfoDb\Application\Command\BuildExtensionHandler;
use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand as AppDiagnoseCommand;
use Bartlett\CompatInfoDb\Application\Command\DiagnoseHandler;
use Bartlett\CompatInfoDb\Application\Command\InitCommand as AppInitCommand;
use Bartlett\CompatInfoDb\Application\Command\InitHandler;
use Bartlett\CompatInfoDb\Application\Command\ListCommand as AppListCommand;
use Bartlett\CompatInfoDb\Application\Command\ListHandler;
use Bartlett\CompatInfoDb\Application\Command\PublishCommand as AppPublishCommand;
use Bartlett\CompatInfoDb\Application\Command\PublishHandler;
use Bartlett\CompatInfoDb\Application\Command\ReleaseCommand as AppReleaseCommand;
use Bartlett\CompatInfoDb\Application\Command\ReleaseHandler;
use Bartlett\CompatInfoDb\Application\Command\ShowCommand as AppShowCommand;
use Bartlett\CompatInfoDb\Application\Command\ShowHandler;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Presentation\Console\Command\BuildExtensionCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\InitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ListCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\PublishCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ShowCommand;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use RuntimeException;

use function array_key_exists;
use function call_user_func;
use function sprintf;

class ContainerService implements ContainerInterface
{
    private $internalServices = [
        CommandBus::class => null,
        BuildExtensionCommand::class => null,
        DiagnoseCommand::class => null,
        InitCommand::class => null,
        ListCommand::class => null,
        PublishCommand::class => null,
        ReleaseCommand::class => null,
        ShowCommand::class => null,
    ];

    // Services allowed at runtime
    private $runtimeServices = [
        InputInterface::class => null,
        OutputInterface::class => null,
        HandlerLocator::class => null,
    ];

    public function __construct(string $referenceDir)
    {
        $this->internalServices[JsonFileHandler::class] = function() use ($referenceDir) {
            return new JsonFileHandler($referenceDir);
        };

        if ($this->has(HandlerLocator::class)) {
            $locator = $this->get(HandlerLocator::class);
        } else {
            $locator = new InMemoryLocator();
        }

        $this->internalServices[CommandBus::class] = function() use ($locator) {
            $handlerMiddleware = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                $locator,
                new InvokeInflector()
            );

            return new CommandBus([$handlerMiddleware]);
        };
        $this->internalServices[BuildExtensionCommand::class] = function() use ($locator) {
            $locator->addHandler(new BuildExtensionHandler(), AppBuildExtensionCommand::class);
            return new BuildExtensionCommand($this->get(CommandBus::class));
        };
        $this->internalServices[DiagnoseCommand::class] = function() use ($locator) {
            $locator->addHandler(new DiagnoseHandler(), AppDiagnoseCommand::class);
            return new DiagnoseCommand($this->get(CommandBus::class));
        };
        $this->internalServices[InitCommand::class] = function() use ($locator) {
            $locator->addHandler(new InitHandler($this->get(JsonFileHandler::class)), AppInitCommand::class);
            return new InitCommand($this->get(CommandBus::class));
        };
        $this->internalServices[ListCommand::class] = function() use ($locator) {
            $locator->addHandler(new ListHandler(), AppListCommand::class);
            return new ListCommand($this->get(CommandBus::class));
        };
        $this->internalServices[PublishCommand::class] = function() use ($locator) {
            $locator->addHandler(new PublishHandler($this->get(JsonFileHandler::class)), AppPublishCommand::class);
            return new PublishCommand($this->get(CommandBus::class));
        };
        $this->internalServices[ReleaseCommand::class] = function() use ($locator) {
            $locator->addHandler(new ReleaseHandler($this->get(JsonFileHandler::class)), AppReleaseCommand::class);
            return new ReleaseCommand($this->get(CommandBus::class));
        };
        $this->internalServices[ShowCommand::class] = function() use ($locator) {
            $locator->addHandler(new ShowHandler(), AppShowCommand::class);
            return new ShowCommand($this->get(CommandBus::class));
        };
    }

    public function set(string $id, $service): void
    {
        if (!array_key_exists($id, $this->runtimeServices)) {
            throw new class(
                sprintf('The "%s" runtime service is not expected.', $id)
            ) extends RuntimeException implements ContainerExceptionInterface {};
        }

        $this->runtimeServices[$id] = $service;
    }

    public function get($id)
    {
        if (isset($this->runtimeServices[$id])) {
            return $this->runtimeServices[$id];
        }

        if (isset($this->internalServices[$id])) {
            return call_user_func($this->internalServices[$id]);
        }

        throw new class(
            sprintf('The "%s" service is not registered in the service container.', $id)
        ) extends RuntimeException implements NotFoundExceptionInterface {};
    }

    public function has($id)
    {
        return isset($this->internalServices[$id]) || isset($this->runtimeServices[$id]);
    }
}
