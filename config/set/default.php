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
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Repository\ClassRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ConstantRepository;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\FunctionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\PlatformRepository;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Command\MessengerCommandBus;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Query\MessengerQueryBus;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ClassRepository as InfrastructureClassRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ConstantRepository as InfrastructureConstantRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\DistributionRepository as InfrastructureDistributionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ExtensionRepository as InfrastructureExtensionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\FunctionRepository as InfrastructureFunctionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\PlatformRepository as InfrastructurePlatformRepository;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CommandInterface;
use function Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\service;

use Composer\Semver\VersionParser;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Command\DebugCommand;

/**
 * Build the Container with default parameters and services
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 3.0.0
 * @author Laurent Laville
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/common.php');
    $containerConfigurator->import(__DIR__ . '/../packages/messenger.php');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    // @link https://symfony.com/doc/current/service_container/tags.html#autoconfiguring-tags
    $services->instanceof(CommandInterface::class)
        ->tag('console.command')
    ;

    $services->set(CommandBusInterface::class, MessengerCommandBus::class);
    $services->set(QueryBusInterface::class, MessengerQueryBus::class);

    // @link https://symfony.com/doc/current/service_container/tags.html#autoconfiguring-tags
    $services->instanceof(CommandHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'command.bus'])
    ;

    $services->instanceof(QueryHandlerInterface::class)
        ->tag('messenger.message_handler', ['bus' => 'query.bus'])
    ;

    if (getenv('APP_ENV') === 'dev') {
        $services->set('console.command.messenger_debug', DebugCommand::class)
            ->args([[]])
            ->tag('console.command')
        ;
    }

    $services->load('Bartlett\CompatInfoDb\\', __DIR__ . '/../../src');

    $services->set(JsonFileHandler::class);
    $services->set(VersionParser::class);
    $services->set(ExtensionFactory::class)
        // for Unit Tests
        ->public()
    ;

    $services->alias(DistributionRepository::class, InfrastructureDistributionRepository::class);
    $services->alias(PlatformRepository::class, InfrastructurePlatformRepository::class);
    $services->alias(ExtensionRepository::class, InfrastructureExtensionRepository::class);
    $services->alias(FunctionRepository::class, InfrastructureFunctionRepository::class);
    $services->alias(ConstantRepository::class, InfrastructureConstantRepository::class);
    $services->alias(ClassRepository::class, InfrastructureClassRepository::class);

    $dbUrl = getenv('APP_DATABASE_URL');
    $url = preg_replace('#^((?:pdo_)?sqlite3?):///#', '$1://localhost/', $dbUrl);
    $url = parse_url($url);

    if ('sqlite' === $url['scheme']) {
        $cacheDir = dirname($url['path']);
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
            touch($url['path']);
        }
    }
    $connectionParams = ['url' => $dbUrl];

    $services->set(EntityManagerInterface::class)
        ->factory([service(EntityManagerFactory::class), 'create'])
        ->arg('$connection', $connectionParams)
        ->arg('$isDevMode', getenv('APP_ENV') === 'dev')
        ->arg('$proxyDir', getenv('APP_PROXY_DIR'))
        // for Doctrine Command Line Interface
        ->public()
    ;
};
