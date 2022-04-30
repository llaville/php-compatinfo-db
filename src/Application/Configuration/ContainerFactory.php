<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Configuration;

use Bartlett\CompatInfoDb\Application\Kernel;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Exception;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
final class ContainerFactory
{
    /**
     * @throws Exception
     */
    public function createFromInput(InputInterface $input = null): ContainerInterface
    {
        if (null === $input) {
            $input = new ArgvInput();
        }
        $configResolver = new ConfigResolver($input);
        return (new Kernel())->createFromConfigs($configResolver->provide());
    }
}
