<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\CompatInfoDb\Domain\Repository\ClassRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ConstantRepository;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\FunctionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ClassRepository as InfrastructureClassRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ConstantRepository as InfrastructureConstantRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\DistributionRepository as InfrastructureDistributionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ExtensionRepository as InfrastructureExtensionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\FunctionRepository as InfrastructureFunctionRepository;
use function Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\service;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Build the Container with Doctrine ORM services
 *
 * @link https://www.doctrine-project.org/projects/orm.html
 *
 * @since 4.4.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->alias(DistributionRepository::class, InfrastructureDistributionRepository::class);
    $services->alias(ExtensionRepository::class, InfrastructureExtensionRepository::class);
    $services->alias(FunctionRepository::class, InfrastructureFunctionRepository::class);
    $services->alias(ConstantRepository::class, InfrastructureConstantRepository::class);
    $services->alias(ClassRepository::class, InfrastructureClassRepository::class);

    $services->set(EntityManagerInterface::class)
        ->factory([service(EntityManagerFactory::class), 'create'])
        ->arg('$isDevMode', getenv('APP_ENV') === 'dev')
        ->arg('$proxyDir', '%compat_info_db.proxy_dir%')
        // for Doctrine Command Line Interface
        ->public()
    ;
};
