<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Kernel;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Phar;
use LogicException;
use ReflectionObject;
use function dirname;
use function getenv;
use function is_file;
use function sys_get_temp_dir;
use const DIRECTORY_SEPARATOR;
use const PATH_SEPARATOR;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
trait MicroKernelTrait
{
    protected ContainerInterface $container;

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @inheritDoc
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @inheritDoc
     */
    public function getHomeDir(): string
    {
        if (PATH_SEPARATOR === ';') {
            // windows
            $homeDir = getenv('USERPROFILE');
        } else {
            // unix
            $homeDir = getenv('HOME');
        }
        return $homeDir;
    }

    /**
     * @inheritDoc
     */
    public function getProjectDir(): string
    {
        if (null === $this->projectDir) {
            if (Phar::running()) {
                $phar = new Phar($_SERVER['argv'][0]);
                $this->projectDir = 'phar://' . $phar->getAlias();
                return $this->projectDir;
            }

            $r = new ReflectionObject($this);

            if (!is_file($dir = $r->getFileName())) {
                throw new LogicException(\sprintf('Cannot auto-detect project dir for kernel of class "%s".', $r->name));
            }

            $dir = $rootDir = dirname($dir);
            while (!is_file($dir . DIRECTORY_SEPARATOR . 'composer.json')) {
                if ($dir === dirname($dir)) {
                    return $this->projectDir = $rootDir;
                }
                $dir = dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir(?string $default = null): string
    {
        $cacheDir = $_SERVER['APP_CACHE_DIR'] ?? $_ENV['APP_CACHE_DIR'] ?? null;

        if (null === $cacheDir) {
            return $default ?? sys_get_temp_dir();
        }
        return $cacheDir;
    }

    /**
     * @inheritDoc
     */
    public function getLogDir(): string
    {
        return $_SERVER['APP_LOG_DIR'] ?? $_ENV['APP_LOG_DIR'] ?? $this->getCacheDir();
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->initializeContainer();
    }

    abstract protected function initializeContainer();

    /**
     * Gets the path to the configuration directory.
     */
    protected function getConfigDir(): string
    {
        return $this->getProjectDir() . DIRECTORY_SEPARATOR . 'config';
    }
}
