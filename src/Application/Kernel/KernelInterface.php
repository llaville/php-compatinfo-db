<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Kernel;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use Exception;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
interface KernelInterface
{
    /**
     * @param string[] $configFiles
     * @throws Exception
     */
    public function createFromConfigs(array $configFiles): ContainerInterface;

    /**
     * @param string[] $configFiles
     * @param list<CompilerPassInterface> $compilerPasses
     * @param list<ExtensionInterface> $extensions
     * @throws Exception
     */
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []): ContainerInterface;
}
