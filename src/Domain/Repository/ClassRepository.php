<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;

/**
 * @since Release 3.2.0
 */
interface ClassRepository extends RepositoryInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $name
     * @param bool $isInterface
     * @return Class_|null
     */
    public function getClassByName(string $name, bool $isInterface): ?Class_;
}
