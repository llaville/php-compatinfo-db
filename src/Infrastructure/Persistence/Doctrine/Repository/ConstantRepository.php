<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository;

use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\ConstantRepository as DomainRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Constant_ as ConstantEntity;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ConstantHydrator;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use function version_compare;
use const PHP_VERSION;

/**
 * @since Release 3.2.0
 * @author Laurent Laville
 */
final class ConstantRepository implements DomainRepository
{
    use EntityManagerTrait;

    /** @var EntityRepository<ConstantEntity> */
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->setEntityManager($entityManager);
        $this->repository = $entityManager->getRepository(ConstantEntity::class);
    }

    /**
     * @inheritDoc
     * @return Constant_[]
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

    public function getConstantByName(string $name, ?string $declaringClass): ?Constant_
    {
        $criteria = new Criteria();

        if (strpos($name, '\\') === false) {
            // standard constant should be uppercase in database
            $criteria->where(Criteria::expr()->eq('name', strtoupper($name)));
        } else {
            // special case for constants that have namespace like in ast extension
            $criteria->where(Criteria::expr()->eq('name', $name));
        }
        $criteria->andWhere(Criteria::expr()->eq('declaringClass', $declaringClass));
        $criteria->orderBy(['phpMin' => 'desc']);

        $collection = $this->repository->matching($criteria);

        $entity = $collection->isEmpty()
            ? null
            : $collection->filter(
                fn($function) => version_compare($function->getPhpMin(), PHP_VERSION, 'le')
            )->first()
        ;

        if (empty($entity)) {
            // function does not exists
            return null;
        }

        return (new ConstantHydrator())->toDomain($entity);
    }
}
