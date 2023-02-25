<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Messenger\MessageBus;

use Exception;
use function dirname;
use function implode;
use const DIRECTORY_SEPARATOR;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
final class CompatInfoDbExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function getNamespace(): string
    {
        return '';
    }

    /**
     * @throws Exception
     * @return void
     *
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configFiles = [
            implode(DIRECTORY_SEPARATOR, ['set', 'common.php']),
            implode(DIRECTORY_SEPARATOR, ['set', 'default.php']),
        ];
        $paths = [
            implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 5), 'config']),
        ];
        $loader = new PhpFileLoader($container, new FileLocator($paths));
        foreach ($configFiles as $configFile) {
            if ($loader->supports($configFile)) {
                $loader->load($configFile);
            }
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias() . '.proxy_dir', $config['proxy_dir']);

        if ($container::willBeAvailable('symfony/console', Application::class, ['bartlett/php-compatinfo-db'])) {
            $loader->load(implode(DIRECTORY_SEPARATOR, ['packages', 'console.php']));
        }

        if ($container::willBeAvailable('doctrine/orm', EntityManager::class, ['bartlett/php-compatinfo-db'])) {
            $loader->load(implode(DIRECTORY_SEPARATOR, ['packages', 'doctrine.php']));
        }

        if ($container::willBeAvailable('symfony/messenger', MessageBus::class, ['bartlett/php-compatinfo-db'])) {
            $loader->load(implode(DIRECTORY_SEPARATOR, ['packages', 'messenger.php']));
        }
    }
}
