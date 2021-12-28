<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Console Application contract.
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
interface ApplicationInterface extends ContainerAwareInterface
{
    public const NAME = 'Database handler for CompatInfo';

    /**
     * @param CommandLoaderInterface $commandLoader
     * @return void
     */
    public function setCommandLoader(CommandLoaderInterface $commandLoader);

    public function getInstalledVersion(bool $withRef = true, string $packageName = 'bartlett/php-compatinfo-db'): string;
}
