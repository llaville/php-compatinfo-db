<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Bartlett\CompatInfoDb\Application\Kernel\KernelInterface;
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
     * @return void
     */
    public function setCommandLoader(CommandLoaderInterface $commandLoader);

    /**
     * Gets the name of the application.
     *
     * @return string
     */
    public function getName();

    /**
     * Gets the current version installed of the application.
     */
    public function getInstalledVersion(bool $withRef = true): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getApplicationParameters(): array;

    /**
     * Gets the current light kernel used by this application.
     */
    public function getKernel(): object;
}
