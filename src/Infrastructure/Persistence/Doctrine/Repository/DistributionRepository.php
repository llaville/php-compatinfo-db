<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository as DomainRepositoryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform as PlatformEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ExtensionHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\PlatformHydrator;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class DistributionRepository implements DomainRepositoryInterface
{
    /** @var EntityRepository<PlatformEntity> */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(PlatformEntity::class);
    }

    public function getDistributionByVersion(string $version): ?Platform
    {
        /** @var PlatformEntity|null $entity */
        $entity = $this->repository->findOneBy(['description' => self::DISTRIBUTION_DESC, 'version' => $version]);

        if (null === $entity) {
            // distribution does not exist
            return null;
        }

        return (new PlatformHydrator())->toDomain($entity);
    }

    /**
     * @inheritDoc
     */
    public function initialize(Collection $collection, string $distVersion): Platform
    {
        $hydrator = new ExtensionHydrator();
        $extensions = $hydrator->hydrateArrays($collection->toArray());

        $hydrator = new PlatformHydrator();
        $platform = $hydrator->hydrate([
            'description' => self::DISTRIBUTION_DESC,
            'version' => $distVersion,
            'created_at' => new DateTimeImmutable(),
            'extensions' => $extensions,
        ]);

        $this->entityManager->persist($platform);
        $this->entityManager->flush();

        return (new PlatformHydrator())->toDomain($platform);
    }

    /**
     * @throws Exception
     */
    public function clear(): void
    {
        $conn = $this->entityManager->getConnection();
        $dbPlatform = $conn->getDatabasePlatform();

        if ($dbPlatform instanceof SQLitePlatform) {
            $foreignKeyChecksQuery = "PRAGMA foreign_keys = OFF;";
            $truncateQuery = "DELETE FROM";
        } else {
            $foreignKeyChecksQuery = "SET FOREIGN_KEY_CHECKS = 0;";
            $truncateQuery = "TRUNCATE TABLE";
        }
        $conn->prepare($foreignKeyChecksQuery)->executeQuery();

        foreach ($conn->createSchemaManager()->listTableNames() as $tableName) {
            $this->entityManager->getConnection()->prepare($truncateQuery . ' ' . $tableName)->executeQuery();
        }

        if ($dbPlatform instanceof SQLitePlatform) {
            $foreignKeyChecksQuery = "PRAGMA foreign_keys = ON;";
        } else {
            $foreignKeyChecksQuery = "SET FOREIGN_KEY_CHECKS = 1;";
        }
        $conn->prepare($foreignKeyChecksQuery)->executeQuery();
    }
}
