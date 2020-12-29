<?php declare(strict_types=1);

/**
 * Handler to list references in the database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\ListRef;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\PlatformRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

use Doctrine\Common\Collections\ArrayCollection;

use function extension_loaded;
use function phpversion;

/**
 * @since Release 2.0.0RC1
 */
final class ListHandler implements QueryHandlerInterface, ExtensionVersionProviderInterface
{
    /** @var PlatformRepository*/
    private $platformRepository;

    /** @var DistributionRepository */
    private $distributionRepository;

    use ExtensionVersionProviderTrait;

    /**
     * ListHandler constructor.
     *
     * @param PlatformRepository $platformRepository
     * @param DistributionRepository $distributionRepository
     */
    public function __construct(
        PlatformRepository $platformRepository,
        DistributionRepository  $distributionRepository
    ) {
        $this->platformRepository = $platformRepository;
        $this->distributionRepository = $distributionRepository;
    }

    /**
     * @param ListQuery $query
     * @return Platform|null
     */
    public function __invoke(ListQuery $query): ?Platform
    {
        if ($query->isInstalled()) {
            $phpVersion = phpversion();

            /** @var Platform|null $platform */
            $platform = $this->platformRepository->getPlatformByVersion($phpVersion);

            if (null === $platform) {
                $platform = $this->initPlatform($phpVersion, $query->getAppVersion());
            }
        } else {
            $platform = $this->distributionRepository->getDistributionByVersion($query->getAppVersion());
        }

        return $platform;
    }

    /**
     * @param string $phpVersion
     * @param string $appVersion
     * @return Platform
     */
    private function initPlatform(string $phpVersion, string $appVersion): Platform
    {
        /** @var Platform $distribution */
        $distribution = $this->distributionRepository->getDistributionByVersion($appVersion);

        $collection = new ArrayCollection();

        foreach ($distribution->getExtensions() as $entity) {
            $name = $entity->getName();
            if (strcasecmp('opcache', $entity->getName()) === 0) {
                // special case
                $name = 'Zend ' . $name;
            }
            if (!extension_loaded($name)) {
                continue;
            }
            $collection->add($entity);
        }

        return $this->platformRepository->initialize($collection, $phpVersion);
    }
}
