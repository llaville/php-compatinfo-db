<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

/**
 * Build the Container with Symfony Messenger services
 *
 * @link https://symfony.com/components/Messenger
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 6.0.0
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
};
