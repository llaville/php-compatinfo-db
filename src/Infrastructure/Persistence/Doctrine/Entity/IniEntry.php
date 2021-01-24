<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, OneToMany, Table, Column, ManyToOne};

/**
 * @Entity
 * @Table(name="ini_entries")
 * @since Release 3.0.0
 */
class IniEntry
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="iniEntries")
     * @var Extension
     */
    private $extension;

    /**
     * @OneToMany(targetEntity=IniRelationship::class, cascade={"persist"}, mappedBy="ini")
     * @var Collection
     */
    private $relationships;


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('IniEntry (id: %s, version: "%s %s")', $this->id, $this->extMin, $this->phpMin);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Extension
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }

    /**
     * @param Extension $extension
     */
    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @param Collection $dependencies
     * @return IniEntry
     */
    public function addDependencies(Collection $dependencies): self
    {
        /** @var Dependency $dependency */
        foreach ($dependencies as $dependency) {
            $relationship = new IniRelationship();
            $relationship->setDependency($dependency);
            $relationship->setIni($this);

            if (!$this->relationships->contains($relationship)) {
                $this->relationships->add($relationship);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        $dependencies = [];
        foreach ($this->relationships as $relationship) {
            $dependencies[] = $relationship->getDependency();
        }
        return $dependencies;
    }
}
