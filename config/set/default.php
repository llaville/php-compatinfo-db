<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactoryInterface;

use Composer\Semver\VersionParser;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Build the Container with default parameters and services
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    $services->set(JsonFileHandler::class);
    $services->set(VersionParser::class);
    $services->set(ExtensionFactoryInterface::class, ExtensionFactory::class)
        // for Unit Tests and examples
        ->public()
    ;

    $services->load('Bartlett\\CompatInfoDb\\', __DIR__ . '/../../src');
};
