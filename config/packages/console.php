<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\CompatInfoDb\Presentation\Console\Application;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CommandInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\Debug\ContainerDebugCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\FactoryCommandLoader;
use Bartlett\CompatInfoDb\Presentation\Console\Input\Input;
use Bartlett\CompatInfoDb\Presentation\Console\Output\Output;
use function Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\service;

use Symfony\Bundle\FrameworkBundle\Command\EventDispatcherDebugCommand;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

/**
 * Build the Container with Symfony Console services
 *
 * @link https://github.com/symfony/console
 *
 * @since 4.4.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    // @link https://symfony.com/doc/current/service_container/tags.html#autoconfiguring-tags
    $services->instanceof(CommandInterface::class)
        ->tag('console.command')
    ;

    if (getenv('APP_ENV') === 'dev') {
        $services->set('console.command.container_debug', ContainerDebugCommand::class)
            ->tag('console.command')
        ;

        $services->set('console.command.eventdispatcher_debug', EventDispatcherDebugCommand::class)
            ->tag('console.command')
        ;
    }

    $services->set(InputInterface::class, Input::class);
    $services->set(OutputInterface::class, Output::class);

    $services->set(ApplicationInterface::class, Application::class)
        ->call('setDispatcher', [service(EventDispatcherInterface::class)])
        ->call('setCommandLoader', [service(CommandLoaderInterface::class)])
        ->call('setContainer', [service(ContainerInterface::class)])
        // for kernel file
        ->public()
    ;

    // @link https://symfony.com/doc/current/console/lazy_commands.html#factorycommandloader
    $services->set(CommandLoaderInterface::class, FactoryCommandLoader::class)
        ->arg('$commands', tagged_iterator('console.command'))
        ->arg('$isDevMode', getenv('APP_ENV') === 'dev')
    ;

    $services->load('Bartlett\\CompatInfoDb\\Presentation\\Console\\', __DIR__ . '/../../src/Presentation/Console');
};
