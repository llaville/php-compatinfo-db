<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;

/**
 * Build a PSR-11 compatible container for console application.
 *
 * @link https://www.php-fig.org/psr/psr-11/
 * @link https://symfony.com/doc/current/components/dependency_injection.html#avoiding-your-code-becoming-dependent-on-the-container
 * @since 3.0.0 in config/container.php
 * @since Release 3.14.0
 */
class ContainerFactory
{
    public function create(string $set = 'default'): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addCompilerPass(new MessengerPass());

        $loader = new PhpFileLoader($containerBuilder, new FileLocator(dirname(__DIR__, 5) . '/config/set'));
        $loader->load($set . '.php');
        $containerBuilder->compile();
        return $containerBuilder;
    }
}
