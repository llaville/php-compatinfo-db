<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Bartlett\CompatInfoDb\Application\Command\BuildExtensionHandler;
use Bartlett\CompatInfoDb\Application\Command\DiagnoseHandler;
use Bartlett\CompatInfoDb\Application\Command\InitHandler;
use Bartlett\CompatInfoDb\Application\Command\ListHandler;
use Bartlett\CompatInfoDb\Application\Command\PublishHandler;
use Bartlett\CompatInfoDb\Application\Command\ReleaseHandler;
use Bartlett\CompatInfoDb\Application\Command\ShowHandler;
use Bartlett\CompatInfoDb\Application\JsonFileHandler;
use Bartlett\CompatInfoDb\DatabaseFactory;
use Bartlett\CompatInfoDb\Presentation\Console\Command\BuildExtensionCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\InitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ListCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\PublishCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ShowCommand;
use Bartlett\CompatInfoDb\Application\Command\ListCommand as AppListCommand;
use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand as AppDiagnoseCommand;
use Bartlett\CompatInfoDb\Application\Command\InitCommand as AppInitCommand;
use Bartlett\CompatInfoDb\Application\Command\ReleaseCommand as AppReleaseCommand;
use Bartlett\CompatInfoDb\Application\Command\PublishCommand as AppPublishCommand;
use Bartlett\CompatInfoDb\Application\Command\ShowCommand as AppShowCommand;
use Bartlett\CompatInfoDb\Application\Command\BuildExtensionCommand as AppBuildExtensionCommand;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use PDO;

/**
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class Application extends \Symfony\Component\Console\Application
{
    /** @var string */
    private $baseDir;

    public function __construct(string $name = 'UNKNOWN')
    {
        try {
            $version = \Jean85\PrettyVersions::getVersion('bartlett/php-compatinfo-db')->getPrettyVersion();
        } catch (\OutOfBoundsException $e) {
            $version = 'UNKNOWN';
        }
        parent::__construct($name, $version);

        $this->baseDir = dirname(dirname(dirname(__DIR__)));
    }

    protected function getDefaultCommands() : array
    {
        $locator = new InMemoryLocator();
        $locator->addHandler(new ListHandler(), AppListCommand::class);
        $locator->addHandler(new DiagnoseHandler(), AppDiagnoseCommand::class);
        $locator->addHandler(new BuildExtensionHandler(), AppBuildExtensionCommand::class);
        $locator->addHandler(new ShowHandler(), AppShowCommand::class);
        $locator->addHandler(new InitHandler(new JsonFileHandler($this->getRefDir())), AppInitCommand::class);
        $locator->addHandler(new ReleaseHandler(new JsonFileHandler($this->getRefDir())), AppReleaseCommand::class);
        $locator->addHandler(new PublishHandler(new JsonFileHandler($this->getRefDir())), AppPublishCommand::class);

        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $locator,
            new InvokeInflector()
        );

        $commandBus = new CommandBus([$handlerMiddleware]);

        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new ListCommand($commandBus);
        $defaultCommands[] = new DiagnoseCommand($commandBus);
        $defaultCommands[] = new InitCommand($commandBus);
        $defaultCommands[] = new BuildExtensionCommand($commandBus);
        $defaultCommands[] = new ReleaseCommand($commandBus);
        $defaultCommands[] = new PublishCommand($commandBus);
        $defaultCommands[] = new ShowCommand($commandBus);

        return $defaultCommands;
    }

    public function getDbFilename() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'compatinfo.sqlite';
    }

    public function getRefDir() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'references';
    }

    public function getLongVersion() : string
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                $v = $this->getDbVersions();

                return sprintf(
                    '<info>%s</info> version <comment>%s</comment> DB built <comment>%s</comment>',
                    $this->getName(),
                    $this->getVersion(),
                    $v['build.string']
                );
            }

            return $this->getName();
        }

        return 'Console Tool';
    }

    public function getDbVersions() : array
    {
        $pdo = DatabaseFactory::create('sqlite');

        $stmt = $pdo->prepare(
            'SELECT build_string as "build.string", build_date as "build.date", build_version as "build.version"' .
            ' FROM bartlett_compatinfo_versions'
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
