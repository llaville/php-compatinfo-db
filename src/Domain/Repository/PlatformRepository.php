<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;

use Doctrine\Common\Collections\Collection;

/**
 * @since Release 3.0.0
 */
interface PlatformRepository extends RepositoryInterface
{
    /**
     * @param string $version
     * @return Platform|null
     */
    public function getPlatformByVersion(string $version): ?Platform;

    /**
     * @param Collection<int, Extension> $collection
     * @param string $phpVersion
     * @return Platform
     */
    public function initialize(Collection $collection, string $phpVersion): Platform;
}
