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
 * @Table(name="ini_relationships",
 *    uniqueConstraints={@UniqueConstraint(name="ini_dependency_unique", columns={"ini_id", "dependency_id"})}
 * )
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class IniRelationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=IniEntry::class, cascade={"persist"}, inversedBy="relationships")
     */
    private IniEntry $ini;

    /**
     * @ManyToOne(targetEntity=Dependency::class, cascade={"persist"})
     */
    private Dependency $dependency;

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
