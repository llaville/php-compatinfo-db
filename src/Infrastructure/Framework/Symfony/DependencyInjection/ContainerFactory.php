<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use Exception;
use function dirname;
use function getcwd;
use function implode;
use function sprintf;
use const DIRECTORY_SEPARATOR;

/**
 * Build a PSR-11 compatible container for console application.
 *
 * @link https://www.php-fig.org/psr/psr-11/
 * @link https://symfony.com/doc/current/components/dependency_injection.html#avoiding-your-code-becoming-dependent-on-the-container
 * @since 3.0.0 in config/container.php
 * @since Release 3.14.0
 * @author Laurent Laville
 */
final class ContainerFactory
{
    /**
     * @param string[] $configFiles
     * @param list<CompilerPassInterface> $compilerPasses
     * @param list<ExtensionInterface> $extensions
     * @throws Exception
     */
    public function create(array $configFiles, array $compilerPasses, array $extensions): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();

        foreach ($compilerPasses as $compilerPass) {
            if ($compilerPass instanceof CompilerPassInterface) {
                $containerBuilder->addCompilerPass($compilerPass);
            }
        }

        $paths = [
            getcwd(),
            implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 5), 'config', 'set']),
        ];
        $loader = new PhpFileLoader($containerBuilder, new FileLocator($paths));
        foreach ($configFiles as $configFile) {
            if ($loader->supports($configFile)) {
                $loader->load($configFile);
            } else {
                throw new FileLocatorFileNotFoundException(
                    sprintf('Format of file "%s" is not supported.', $configFile)
                );
            }
        }

        foreach ($extensions as $extension) {
            if ($extension instanceof ExtensionInterface) {
                $containerBuilder->registerExtension($extension);
            }
        }

        return $containerBuilder;
    }
}
