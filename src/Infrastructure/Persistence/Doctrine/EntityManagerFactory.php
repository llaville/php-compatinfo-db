<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine;

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;

use Psr\Cache\CacheItemPoolInterface;

use function getenv;
use function implode;
use function sprintf;
use function str_replace;
use const DIRECTORY_SEPARATOR;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class EntityManagerFactory
{
    /**
     * @throws ORMException
     */
    public static function create(bool $isDevMode, string $proxyDir, ?CacheItemPoolInterface $cache = null): EntityManagerInterface
    {
        $paths = [implode(DIRECTORY_SEPARATOR, [__DIR__, 'Entity'])];
        $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);
        if ($isDevMode) {
            // suggested for DEV mode: see Doctrine ORM documentation
            // at https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/advanced-configuration.html#auto-generating-proxy-classes-optional
            $config->setAutogenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_ALWAYS);
        } else {
            // lazy generation on PROD or TEST modes (i.e: CI)
            $config->setAutogenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        }

        return EntityManager::create(self::connection(), $config);
    }

    /**
     * @return array<string, string>
     */
    private static function connection(): array
    {
        $dbUrl = getenv('DATABASE_URL');
        if (false === $dbUrl) {
            $targetFile = 'compatinfo-db.sqlite';
            $dbUrl = sprintf('sqlite:///%s/%s', '%kernel.cache_dir%', $targetFile);
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
