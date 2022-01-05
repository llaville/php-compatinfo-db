<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform as PlatformEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ExtensionHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\PlatformHydrator;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class DistributionRepository implements DomainRepository
{
    use EntityManagerTrait;

    private const DISTRIBUTION_DESC = 'CompatInfoDB';

    /** @var EntityRepository<PlatformEntity> */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(PlatformEntity::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getDistributionByVersion(string $version): ?Platform
    {
        /** @var PlatformEntity|null $entity */
        $entity = $this->repository->findOneBy(['description' => self::DISTRIBUTION_DESC, 'version' => $version]);

        if (null === $entity) {
            // distribution does not exists
            return null;
        }

        return (new PlatformHydrator())->toDomain($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(Collection $collection, string $distVersion): Platform
    {
        $hydrator = new ExtensionHydrator();
        $extensions = $hydrator->hydrateArrays($collection->toArray());

        $platform = new PlatformEntity();
        $platform->setDescription(self::DISTRIBUTION_DESC);
        $platform->setVersion($distVersion);
        $platform->addExtensions($extensions);
        $platform->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($platform);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return (new PlatformHydrator())->toDomain($platform);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): void
    {
        $logger = $this->entityManager->getConnection()->getConfiguration()->getSQLLogger();
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $conn = $this->entityManager->getConnection();

        if ($conn->getDriver()->getDatabasePlatform()->getName() === 'pdo_sqlite') {
            $foreignKeyChecksQuery = "PRAGMA foreign_keys = OFF;";
            $truncateQuery = "DELETE FROM";
        } else {
            $foreignKeyChecksQuery = "SET FOREIGN_KEY_CHECKS = 0;";
            $truncateQuery = "TRUNCATE TABLE";
        }
        $conn->prepare($foreignKeyChecksQuery)->execute();

        foreach ($conn->getSchemaManager()->listTableNames() as $tableName) {
            $this->entityManager->getConnection()->prepare($truncateQuery . ' ' . $tableName)->execute();
        }

        if ($conn->getDriver()->getDatabasePlatform()->getName() === 'pdo_sqlite') {
            $foreignKeyChecksQuery = "PRAGMA foreign_keys = ON;";
        } else {
            $foreignKeyChecksQuery = "SET FOREIGN_KEY_CHECKS = 1;";
        }
        $conn->prepare($foreignKeyChecksQuery)->execute();

        $conn->getConfiguration()->setSQLLogger($logger);
    }
}
