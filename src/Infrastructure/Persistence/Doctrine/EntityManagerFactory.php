<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

use function getenv;
use function implode;
use function str_replace;
use const DIRECTORY_SEPARATOR;
use const PATH_SEPARATOR;

/**
 * @since Release 3.0.0
 */
final class EntityManagerFactory
{
    /**
     * @param array<string, string> $connection
     * @param Cache|null $cache
     * @return EntityManagerInterface
     * @throws ORMException
     */
    public static function create(array $connection, Cache $cache = null): EntityManagerInterface
    {
        $paths = [implode(DIRECTORY_SEPARATOR, [__DIR__, 'Entity'])];
        $isDevMode = false;
        $proxyDir = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'proxies';
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache);

        return EntityManager::create(self::connection($connection), $config);
    }

    /**
     * @param array<string, string> $connection
     * @return array<string, string>
     */
    private static function connection(array $connection): array
    {
        $url = $connection['url'] ?? '';
        if (empty($url)) {
            return $connection;
        }

        if (PATH_SEPARATOR === ';') {
            // windows
            $userHome = getenv('USERPROFILE');
        } else {
            // unix
            $userHome = getenv('HOME');
        }

        $connection['url'] = str_replace(['${HOME}', '%HOME%'], $userHome, $connection['url']);
        return $connection;
    }
}
