<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Repository;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
trait EntityManagerTrait
{
    private EntityManagerInterface $entityManager;

    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
