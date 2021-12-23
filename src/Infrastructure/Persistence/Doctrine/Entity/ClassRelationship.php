<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="class_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="class_dependency_unique", columns={"class_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class ClassRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=Class_::class, cascade={"persist"}, inversedBy="relationships")
     */
    private Class_ $class;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"})
     */
    private Dependency $dependency;

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
