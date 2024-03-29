<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

use Doctrine\Common\Collections\Collection;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
interface DistributionRepository extends RepositoryInterface
{
    public const DISTRIBUTION_DESC = 'CompatInfoDB';

    public function getDistributionByVersion(string $version): ?Platform;

    /**
     * @param Collection<int, mixed> $collection
     */
    public function initialize(Collection $collection, string $distVersion): Platform;

    public function clear(): void;
}
