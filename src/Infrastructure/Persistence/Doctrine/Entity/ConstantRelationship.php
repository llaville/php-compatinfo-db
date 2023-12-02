<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

#[Entity]
#[Table(name: "constant_relationships")]
#[UniqueConstraint(name: "constant_dependency_unique", columns: ["constant_id", "dependency_id"])]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class ConstantRelationship
{
    use PrimaryIdentifierTrait;

    #[ManyToOne(targetEntity: Constant_::class, cascade: ["persist"], inversedBy: "relationships")]
    private Constant_ $constant;

    #[ManyToOne(targetEntity: Dependency::class, cascade: ["persist"])]
    private Dependency $dependency;

    public function setConstant(Constant_ $constant): void
    {
        $this->constant = $constant;
    }

    public function setDependency(Dependency $dependency): void
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): Dependency
    {
        return $this->dependency;
    }
}
