<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\FunctionRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Function_ as FunctionEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\FunctionHydrator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
final class FunctionRepository implements DomainRepository
{
    use EntityManagerTrait;

    /** @var EntityRepository<FunctionEntity> */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(FunctionEntity::class);
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function getFunctionByName(string $name, ?string $declaringClass): ?Function_
    {
        $entity = $this->repository->findOneBy(['name' => $name, 'declaringClass' => $declaringClass]);

        if (null === $entity) {
            // function does not exists
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
