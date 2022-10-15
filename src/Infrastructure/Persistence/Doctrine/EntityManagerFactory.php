<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;

use function getenv;
use function implode;
use function str_replace;
use const DIRECTORY_SEPARATOR;
use const PATH_SEPARATOR;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class EntityManagerFactory
{
    /**
     * @param array<string, string> $connection
     * @param bool $isDevMode
     * @param string $proxyDir
     * @param Cache|null $cache
     * @return EntityManagerInterface
     * @throws ORMException
     */
    public static function create(array $connection, bool $isDevMode, string $proxyDir, ?Cache $cache = null): EntityManagerInterface
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
