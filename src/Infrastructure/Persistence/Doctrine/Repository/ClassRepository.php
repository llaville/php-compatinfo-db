<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\ClassRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Class_ as ClassEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ClassHydrator;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use function version_compare;
use const PHP_VERSION;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
final class ClassRepository implements DomainRepository
{
    /** @var EntityRepository<ClassEntity> */
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(ClassEntity::class);
    }

    /**
     * @inheritDoc
     * @return Class_[]
     */
    public function getAll(): array
    {
        $hydrator = new ClassHydrator();
        $classes = [];
        foreach ($this->repository->findAll() as $entity) {
            $classes[] = $hydrator->toDomain($entity);
        }
        return $classes;
    }

    public function getClassByName(string $name, bool $isInterface): ?Class_
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->eq('name', $name));
        $criteria->andWhere(Criteria::expr()->eq('isInterface', $isInterface));
        $criteria->orderBy(['phpMin' => 'desc']);

        $collection = $this->repository->matching($criteria);

        $entity = $collection->isEmpty()
            ? null
            : $collection->filter(
                fn($function) => version_compare($function->getPhpMin(), PHP_VERSION, 'le')
            )->first()
        ;

        if (empty($entity)) {
            // class does not exist
            return null;
        }

        return (new ClassHydrator())->toDomain($entity);
    }
}
