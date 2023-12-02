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
#[Table(name: "function_relationships")]
#[UniqueConstraint(name: "function_dependency_unique", columns: ["function_id", "dependency_id"])]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class FunctionRelationship
{
    use PrimaryIdentifierTrait;

    #[ManyToOne(targetEntity: Function_::class, cascade:["persist"], inversedBy: "relationships")]
    private Function_ $function;

    #[ManyToOne(targetEntity: Dependency::class, cascade:["persist"])]
    private Dependency $dependency;

    public function setFunction(Function_ $function): void
    {
        $this->function = $function;
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
