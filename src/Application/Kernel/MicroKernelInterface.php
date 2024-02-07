<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Kernel;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
interface MicroKernelInterface
{
    /**
     * Gets the environment.
     */
    public function getEnvironment(): string;

    /**
     * Checks if debug mode is enabled.
     */
    public function isDebug(): bool;

    /**
     * Gets the user home dir.
     */
    public function getHomeDir(): string;

    /**
     * Gets the project dir (path of the project's composer file).
     */
    public function getProjectDir(): string;

    /**
     * Gets the cache directory.
     */
    public function getCacheDir(string $default = null): string;

    /**
     * Gets the log directory.
     */
    public function getLogDir(): string;

    /**
     * Gets the current container.
     */
    public function getContainer(): ContainerInterface;

    /**
     * Boots the current kernel.
     */
    public function boot(): void;
}
