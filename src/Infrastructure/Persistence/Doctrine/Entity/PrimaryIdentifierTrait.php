<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Id, Column, GeneratedValue};

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
trait PrimaryIdentifierTrait
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue(strategy: "AUTO")]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
