<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

use Doctrine\Common\Collections\Collection;

/**
 * @since Release 3.0.0
 */
interface DistributionRepository extends RepositoryInterface
{
    /**
     * @param string $version
     * @return Platform|null
     */
    public function getDistributionByVersion(string $version): ?Platform;

    /**
     * @param Collection $collection
     * @param string $distVersion
     * @return Platform
     */
    public function initialize(Collection $collection, string $distVersion): Platform;

    public function clear(): void;
}
