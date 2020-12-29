<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="ini_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="ini_dependency_unique", columns={"ini_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 */
final class IniRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=IniEntry::class, cascade={"persist"}, inversedBy="relationships")
     * @var IniEntry
     */
    private $ini;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"}, fetch="EAGER")
     * @var Dependency
     */
    private $dependency;

    /**
     * @param IniEntry $ini
     */
    public function setIni(IniEntry $ini): void
    {
        $this->ini = $ini;
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
