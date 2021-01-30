<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;

/**
 * @since Release 3.2.0
 */
interface ConstantRepository extends RepositoryInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $name
     * @param string|null $declaringClass
     * @return Constant_|null
     */
    public function getConstantByName(string $name, ?string $declaringClass): ?Constant_;
}
