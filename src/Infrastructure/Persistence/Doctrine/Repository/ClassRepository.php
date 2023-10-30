<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\ClassRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Class_ as ClassEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ClassHydrator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
final class ClassRepository implements DomainRepository
{
    use EntityManagerTrait;

    /** @var EntityRepository<ClassEntity> */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(ClassEntity::class);
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function getClassByName(string $name, bool $isInterface): ?Class_
    {
        $entity = $this->repository->findOneBy(['name' => $name, 'isInterface' => $isInterface]);

        if (null === $entity) {
            // class does not exist
            return null;
        }

        return (new ClassHydrator())->toDomain($entity);
    }
}
