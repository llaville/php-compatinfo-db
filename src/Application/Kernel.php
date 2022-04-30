<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application;

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\ContainerFactory;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;

use Exception;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 *
 * @link https://tomasvotruba.com/blog/introducing-light-kernel-for-symfony-console-apps/
 */
final class Kernel
{
    /**
     * @param string[] $configFiles
     * @throws Exception
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        return $this->create($configFiles);
    }

    /**
     * @param string[] $configFiles
     * @param list<CompilerPassInterface> $compilerPasses
     * @param list<ExtensionInterface> $extensions
     * @throws Exception
     */
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []): ContainerInterface
    {
        if (empty($compilerPasses)) {
            $compilerPasses[] = new MessengerPass();
        }

        $containerFactory = new ContainerFactory();
        /** @var ContainerBuilder $containerBuilder */
        $containerBuilder = $containerFactory->create($configFiles, $compilerPasses, $extensions);
        $containerBuilder->compile();

        return $containerBuilder;
    }
}
