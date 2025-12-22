<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine;

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;

use Doctrine\DBAL\Driver\PDO\SQLite\Driver;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Proxy\ProxyFactory;

use Psr\Cache\CacheItemPoolInterface;

use function explode;
use function getenv;
use function implode;
use function ltrim;
use function sprintf;
use function str_contains;
use function str_replace;
use function strcasecmp;
use const DIRECTORY_SEPARATOR;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class EntityManagerFactory
{
    /**
     * @throws Exception
     */
    public static function create(
        bool $isDevMode,
        string $proxyDir,
        ?CacheItemPoolInterface $cache = null,
        string $autogenerateProxyClasses = 'auto'
    ): EntityManagerInterface {
        $paths = [implode(DIRECTORY_SEPARATOR, [__DIR__, 'Entity'])];

        if (PHP_VERSION_ID >= 80400) {
            $config = ORMSetup::createAttributeMetadataConfig($paths, $isDevMode);
            $config->enableNativeLazyObjects(true);
        } else {
            $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);

            if ($isDevMode) {
                $autoGenerate = ProxyFactory::AUTOGENERATE_ALWAYS;
            } else {
                $autoGenerate = match ($autogenerateProxyClasses) {
                    'never' => ProxyFactory::AUTOGENERATE_NEVER,
                    'always' => ProxyFactory::AUTOGENERATE_ALWAYS,
                    default => ProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS_OR_CHANGED,
                };
            }
            $config->setAutogenerateProxyClasses($autoGenerate);
        }

        $connection = DriverManager::getConnection(self::connection(), $config);
        return new EntityManager($connection, $config);
    }

    /**
     * @return array{url: string, driverClass: string, driver: string, path: string}
     */
    private static function connection(): array
    {
        $url = getenv('DATABASE_URL');
        if (false === $url) {
            // default database string connection
            $targetFile = 'compatinfo-db.sqlite';
            $path = sprintf('%s/%s', '%kernel.cache_dir%', $targetFile);
            $url = 'sqlite://' . $path;
        }

        $environment = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'prod';
        $kernel = new ConsoleKernel($environment, false);

        $dbUrl = str_replace('%kernel.cache_dir%', $kernel->getCacheDir(), $url);
        $url = str_replace(['${HOME}', '%HOME%'], $kernel->getHomeDir(), $dbUrl);

        if (str_contains($url, '://')) {
            list($driver, $pathResolved) = explode('://', $url);

            if (strcasecmp($driver, 'sqlite') === 0) {
                $driver = 'sqlite3';
                $driverClass = Driver::class;
            }
        }

        $pathResolved ??= '/';

        return [
            'driverClass' => $driverClass ?? null,
            'driver' => $driver ?? null,
            'url' => $url,
            'path' => '/' . ltrim($pathResolved, '/'),
        ];
    }
}
