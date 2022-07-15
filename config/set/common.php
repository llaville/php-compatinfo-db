<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher;
use Bartlett\CompatInfoDb\Application\Event\Subscriber\ProfileEventSubscriber;
use function Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\service;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Build the Container with common parameters and services
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
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

    $services->load('Bartlett\\CompatInfoDb\\Application\\Event\\', __DIR__ . '/../../src/Application/Event');
};
