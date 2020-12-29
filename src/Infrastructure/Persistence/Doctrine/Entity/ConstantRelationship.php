<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="constant_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="constant_dependency_unique", columns={"constant_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 */
final class ConstantRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=Constant_::class, cascade={"persist"}, inversedBy="relationships")
     * @var Constant_
     */
    private $constant;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"}, fetch="EAGER")
     * @var Dependency
     */
    private $dependency;

    /**
     * @param Constant_ $constant
     */
    public function setConstant(Constant_ $constant): void
    {
        $this->constant = $constant;
    }

    /**
     * @param Dependency $dependency
     */
    public function setDependency(Dependency $dependency): void
    {
        $this->dependency = $dependency;
    }

    /**
     * @return Dependency
     */
    public function getDependency(): Dependency
    {
        return $this->dependency;
    }
}
