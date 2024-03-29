<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
interface ConstantRepository extends RepositoryInterface
{
    /**
     * @return Constant_[]
     */
    public function getAll(): array;

    public function getConstantByName(string $name, ?string $declaringClass): ?Constant_;
}
