<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="function_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="function_dependency_unique", columns={"function_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 */
final class FunctionRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=Function_::class, cascade={"persist"}, inversedBy="relationships")
     * @var Function_
     */
    private $function;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"}, fetch="EAGER")
     * @var Dependency
     */
    private $dependency;

    /**
     * @param Function_ $function
     */
    public function setFunction(Function_ $function): void
    {
        $this->function = $function;
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
