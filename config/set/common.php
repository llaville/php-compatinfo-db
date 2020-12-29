<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher;
use Bartlett\CompatInfoDb\Application\Event\Subscriber\ProfileEventSubscriber;
use Bartlett\CompatInfoDb\Presentation\Console\Application;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\FactoryCommandLoader;
use Bartlett\CompatInfoDb\Presentation\Console\Input\Input;
use Bartlett\CompatInfoDb\Presentation\Console\Output\Output;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

/**
 * Build the Container with common parameters and services
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 3.0.0
 */
return static function (ContainerConfigurator $containerConfigurator): void
{
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    $services->set(InputInterface::class, Input::class)
        // for configuration option of bin file
        ->public()
    ;
    $services->set(OutputInterface::class, Output::class)
        // for configuration option of bin file
        ->public()
    ;

    $services->set(ApplicationInterface::class, Application::class)
        ->call('setDispatcher', [service(EventDispatcherInterface::class)])
        // for bin file
        ->public()
    ;

    // @link https://symfony.com/doc/current/console/lazy_commands.html#factorycommandloader
    $services->set(CommandLoaderInterface::class, FactoryCommandLoader::class)
        ->arg('$commands', [tagged_iterator('console.command')])
        // for bin file
        ->public()
    ;

    $services->set(Stopwatch::class);

    $services->set(ProfileEventSubscriber::class)
        ->args([service(Stopwatch::class)])
    ;
    $services->alias(EventSubscriberInterface::class . ' $profileEventSubscriber', ProfileEventSubscriber::class);

    $services->set(EventDispatcherInterface::class, EventDispatcher::class)
        // for unit tests
        ->public()
    ;
};
