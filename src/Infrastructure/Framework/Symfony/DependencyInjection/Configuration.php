<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection;

use Bartlett\CompatInfoDb\Infrastructure\Framework\Composer\InstalledVersions;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function str_replace;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 *
 * @link https://symfony.com/doc/current/components/config/definition.html
 */
final class Configuration implements ConfigurationInterface
{
    private const DEFAULT_PROXY_DIR = "%kernel.cache_dir%/php-compatinfo-db/%version%/proxies";

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('compat_info_db');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->addFolderSection($rootNode);

        return $treeBuilder;
    }

    private function addFolderSection(ArrayNodeDefinition $rootNode): void
    {
        $version = InstalledVersions::getPrettyVersion('bartlett/php-compatinfo-db');

        $defaultProxyDir = str_replace('%version%', $version, self::DEFAULT_PROXY_DIR);
        $proxyDir = ($_SERVER['APP_PROXY_DIR'] ?? $_ENV['APP_PROXY_DIR'] ?? $defaultProxyDir);

        $rootNode
            ->children()
                ->scalarNode('version')->defaultValue($version)->end()
                ->scalarNode('proxy_dir')->defaultValue($proxyDir)->end()
            ->end()
        ;
    }
}
