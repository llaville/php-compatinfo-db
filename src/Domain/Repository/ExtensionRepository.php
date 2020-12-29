<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * @since Release 3.0.0
 */
interface ExtensionRepository extends RepositoryInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $name
     * @return Extension|null
     */
    public function getExtensionByName(string $name): ?Extension;
}
