<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
interface FunctionRepository extends RepositoryInterface
{
    /**
     * @return Function_[]
     */
    public function getAll(): array;

    /**
     * @param string $name
     * @param string|null $declaringClass
     * @return Function_|null
     */
    public function getFunctionByName(string $name, ?string $declaringClass): ?Function_;
}
