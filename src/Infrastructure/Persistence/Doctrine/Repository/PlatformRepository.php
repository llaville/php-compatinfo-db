<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\PlatformRepository as PlatformRepositoryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform as PlatformEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\PlatformHydrator;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 */
final class PlatformRepository implements PlatformRepositoryInterface
{
    private const PLATFORM_DESC = 'PHP Interpreter';

    /** @var EntityRepository<PlatformEntity> */
    private $repository;

    use EntityManagerTrait;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(PlatformEntity::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getPlatformByVersion(string $version): ?Platform
    {
        /** @var PlatformEntity|null $entity */
        $entity = $this->repository->findOneBy(['description' => self::PLATFORM_DESC, 'version' => $version]);

        if (null === $entity) {
            // platform does not exists
            return null;
        }

        return (new PlatformHydrator())->toDomain($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(Collection $collection, string $phpVersion): Platform
    {
        $platform = new PlatformEntity();
        $platform->setDescription(self::PLATFORM_DESC);
        $platform->setVersion($phpVersion);
        $platform->setCreatedAt(new DateTimeImmutable());
        $platform->addExtensions($collection->toArray());

        $this->entityManager->persist($platform);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return (new PlatformHydrator())->toDomain($platform);
    }
}
