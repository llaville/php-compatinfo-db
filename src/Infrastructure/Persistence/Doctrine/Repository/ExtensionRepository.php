<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension as ExtensionEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ExtensionHydrator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * @since Release 3.0.0
 */
final class ExtensionRepository implements DomainRepository
{
    /** @var ObjectRepository */
    private $repository;

    use EntityManagerTrait;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(ExtensionEntity::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        $hydrator = new ExtensionHydrator();
        $extensions = [];
        foreach ($this->repository->findAll() as $entity) {
            $extensions[] = $hydrator->toDomain($entity);
        }
        return $extensions;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensionByName(string $name): ?Extension
    {
        $entity = $this->repository->findOneBy(['name' => $name]);

        if (null === $entity) {
            // extension does not exists
            return null;
        }

        // @FIXME doctrine logger
        //$logger = $this->entityManager->getConfiguration()->getSQLLogger(); var_dump($logger->queries);

        return (new ExtensionHydrator())->toDomain($entity);
    }
}
