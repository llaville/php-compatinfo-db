<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\FunctionRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Function_ as FunctionEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\FunctionHydrator;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use function version_compare;
use const PHP_VERSION;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
final class FunctionRepository implements DomainRepository
{
    /** @var EntityRepository<FunctionEntity> */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(FunctionEntity::class);
    }

    /**
     * @inheritDoc
     * @return Function_[]
     */
    public function getAll(): array
    {
        $hydrator = new FunctionHydrator();
        $functions = [];
        foreach ($this->repository->findAll() as $entity) {
            $functions[] = $hydrator->toDomain($entity);
        }
        return $functions;
    }

    public function getFunctionByName(string $name, ?string $declaringClass): ?Function_
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('name', $name));
        $criteria->andWhere(Criteria::expr()->eq('declaringClass', $declaringClass));
        $criteria->orderBy(['phpMin' => 'desc']);

        $collection = $this->repository->matching($criteria);

        $entity = $collection->isEmpty()
            ? null
            : $collection->first()
        ;

        if (empty($entity)) {
            // function does not exist
            return null;
        }

        $prototype = $entity->getPrototype();
        if (!empty($prototype)) {
            $function = $this->getFunctionByName($name, $prototype);
            $entity->setPhpMax($function->getPhpMax());
            return (new FunctionHydrator())->toDomain($entity);
        }

        return (new FunctionHydrator())->toDomain($entity);
    }
}
