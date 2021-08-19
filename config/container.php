<?php declare(strict_types=1);

/**
 * Build a PSR-11 compatible container for console application.
 *
 * @link https://www.php-fig.org/psr/psr-11/
 * @link https://symfony.com/doc/current/components/dependency_injection.html#avoiding-your-code-becoming-dependent-on-the-container
 * @since 3.0.0
 */

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addCompilerPass(new MessengerPass());

$loader = new PhpFileLoader($containerBuilder, new FileLocator('./config/set'));
$loader->load('default.php');

$containerBuilder->compile();

return $containerBuilder;
