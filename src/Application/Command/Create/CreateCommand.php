<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Create;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Value Object of console db:create command.
 *
 * @since Release 3.19.0
 * @author Laurent Laville
 */
final class CreateCommand implements CommandInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
