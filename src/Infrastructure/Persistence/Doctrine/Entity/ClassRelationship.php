<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="class_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="class_dependency_unique", columns={"class_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 */
class ClassRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=Class_::class, cascade={"persist"}, inversedBy="relationships")
     * @var Class_
     */
    private $class;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"})
     * @var Dependency
     */
    private $dependency;

    /**
     * @param Class_ $class
     */
    public function setClass(Class_ $class): void
    {
        $this->class = $class;
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
