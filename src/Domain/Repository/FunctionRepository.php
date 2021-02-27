<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;

/**
 * @since Release 3.2.0
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
