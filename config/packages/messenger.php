<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Command\MessengerCommandBus;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Query\MessengerQueryBus;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Command\DebugCommand;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

/**
 * Build the Container with Symfony Messenger services
 *
 * @link https://symfony.com/components/Messenger
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('command.bus.middleware', [['id' => 'handle_message']]);
    $parameters->set('query.bus.middleware', [['id' => 'handle_message']]);

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autoconfigure()
        ->autowire()
    ;

    // @link https://symfony.com/doc/current/service_container/tags.html#autoconfiguring-tags
    $services->instanceof(CommandHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'command.bus'])
    ;

    $services->instanceof(QueryHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'query.bus'])
    ;

    $services->set('messenger.middleware.handle_message', HandleMessageMiddleware::class)
        ->abstract()
        ->args([[]])
    ;

    $services->set('command.bus', MessageBus::class)
        ->args([[]])
        ->tag('messenger.bus')
    ;
    $services->alias(MessageBusInterface::class . ' $commandBus', 'command.bus');

    $services->set('query.bus', MessageBus::class)
        ->args([[]])
        ->tag('messenger.bus')
    ;
    $services->alias(MessageBusInterface::class . ' $queryBus', 'query.bus');

    $services->set(CommandBusInterface::class, MessengerCommandBus::class);
    $services->set(QueryBusInterface::class, MessengerQueryBus::class);

    if ('prod' !== $containerConfigurator->env()) {
        $services->set('console.command.messenger_debug', DebugCommand::class)
            ->args([[]])
            ->tag('console.command')
        ;
    }

    $services->load('Bartlett\\CompatInfoDb\\Application\\Command\\', __DIR__ . '/../../src/Application/Command');
    $services->load('Bartlett\\CompatInfoDb\\Application\\Query\\', __DIR__ . '/../../src/Application/Query');
    $services->load('Bartlett\\CompatInfoDb\\Infrastructure\\Bus\\', __DIR__ . '/../../src/Infrastructure/Bus');
};
