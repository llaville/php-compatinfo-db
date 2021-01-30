<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\ConstantRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Constant_ as ConstantEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ConstantHydrator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @since Release 3.2.0
 */
final class ConstantRepository implements DomainRepository
{
    /** @var EntityRepository */
    private $repository;

    use EntityManagerTrait;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(ConstantEntity::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        $hydrator = new ConstantHydrator();
        $constants = [];
        foreach ($this->repository->findAll() as $entity) {
            $constants[] = $hydrator->toDomain($entity);
        }
        return $constants;
    }

    /**
     * {@inheritDoc}
     */
    public function getConstantByName(string $name, ?string $declaringClass): ?Constant_
    {
        $criteria = ['name' => $name];
        if ($declaringClass !== null) {
            $criteria['declaringClass'] = $declaringClass;
        }
        $entity = $this->repository->findOneBy($criteria);

        if (null === $entity) {
            // function does not exists
            return null;
        }

        return (new ConstantHydrator())->toDomain($entity);
    }
}
