<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\PlatformRepository;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Command\MessengerCommandBus;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Query\MessengerQueryBus;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\DistributionRepository as InfrastructureDistributionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ExtensionRepository as InfrastructureExtensionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\PlatformRepository as InfrastructurePlatformRepository;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CommandInterface;

use Composer\Semver\VersionParser;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Command\DebugCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * Build the Container with default parameters and services
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 3.0.0
 */
return static function (ContainerConfigurator $containerConfigurator): void
{
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

    if (extension_loaded('apcu') && ini_get('apc.enable_cli')) {
        $services->set(Cache::class, ApcuCache::class);
    }

    $dbUrl = getenv('DATABASE_URL');
    if (false === $dbUrl) {
        $dbUrl = 'sqlite:///${HOME}/.cache/bartlett/compatinfo-db.sqlite';
        putenv('DATABASE_URL=' . $dbUrl);
    }
    $connectionParams = ['url' => $dbUrl];

    $services->set(EntityManagerInterface::class)
        ->factory([service(EntityManagerFactory::class), 'create'])
        ->arg('$connection', $connectionParams)
        // for Doctrine Command Line Interface
        ->public()
    ;
};
