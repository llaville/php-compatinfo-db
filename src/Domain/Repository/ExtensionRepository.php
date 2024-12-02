<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
interface ExtensionRepository extends RepositoryInterface
{
    /**
     * @return Extension[]
     */
    public function getAll(): array;

    public function getExtensionByName(string $name, ?string $phpVersion): ?Extension;
}
