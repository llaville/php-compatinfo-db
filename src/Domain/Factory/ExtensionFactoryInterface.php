<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Factory;

use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
interface ExtensionFactoryInterface extends ExtensionVersionProviderInterface
{
    /**
     * @param string $name
     * @return Extension|null
     */
    public function create(string $name): ?Extension;
}
