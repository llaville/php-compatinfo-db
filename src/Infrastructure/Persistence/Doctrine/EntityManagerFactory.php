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
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Proxy\ProxyFactory;

use Psr\Cache\CacheItemPoolInterface;

use function getenv;
use function implode;
use function phpversion;
use function sprintf;
use function str_replace;
use function version_compare;
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
    ) : EntityManagerInterface {
        $paths = [implode(DIRECTORY_SEPARATOR, [__DIR__, 'Entity'])];
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);

        if ($isDevMode) {
            $autoGenerate = ProxyFactory::AUTOGENERATE_ALWAYS;
        } else {
            $autoGenerate = match($autogenerateProxyClasses) {
                'never' => ProxyFactory::AUTOGENERATE_NEVER,
                'always' => ProxyFactory::AUTOGENERATE_ALWAYS,
                default => ProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS_OR_CHANGED,
            };
        }
        $config->setAutogenerateProxyClasses($autoGenerate);

        $connection = DriverManager::getConnection(self::connection(), $config);
        return new EntityManager($connection, $config);
    }

    /**
     * @return array{url?: string, driverClass?: string, driver?: string, path ?: string}
     */
    private static function connection(): array
    {
        $connection = [];
        $dbUrl = getenv('DATABASE_URL');
        if (false === $dbUrl) {
            $connection['driverClass'] = Driver::class;
            $connection['driver'] = 'sqlite3';
            $targetFile = 'compatinfo-db.sqlite';
            $driver = $connection['driver'] . '://';
            $path = sprintf('%s/%s', '%kernel.cache_dir%', $targetFile);
            $pathResolved = self::resolve($path);
            $connection['path'] = $pathResolved;
            $connection['url'] = $driver . $pathResolved;
            return $connection;
        }
        $connection['url'] = self::resolve($dbUrl);
        return $connection;
    }

    private static function resolve(string $url): string
    {
        $environment = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'prod';
        $kernel = new ConsoleKernel($environment, false);

        $dbUrl = str_replace('%kernel.cache_dir%', $kernel->getCacheDir(), $url);

        return str_replace(['${HOME}', '%HOME%'], $kernel->getHomeDir(), $dbUrl);
    }
}
