<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @since Release 3.0.0
 */
trait EntityManagerTrait
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
