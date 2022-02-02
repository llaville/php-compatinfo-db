<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;

use Doctrine\Common\Collections\Collection;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
interface PlatformRepository extends RepositoryInterface
{
    public const PLATFORM_DESC = 'PHP Interpreter';

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
