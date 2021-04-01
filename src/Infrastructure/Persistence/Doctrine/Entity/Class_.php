<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, OneToMany, Table, Column, ManyToOne};

/**
 * @Entity
 * @Table(name="classes")
 * @since Release 3.0.0
 */
class Class_
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
     * @Column(name="interface", type="boolean")
     * @var bool
     */
    private $isInterface;

    /**
     * @Column(name="flags", type="integer")
     * @var int
     */
    private $flags;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="classes")
     * @var Extension
     */
    private $extension;

    /**
     * @OneToMany(targetEntity=ClassRelationship::class, cascade={"persist"}, mappedBy="class")
     * @var Collection<int, ClassRelationship>
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
        return sprintf('Class (id: %s, version: "%s %s")', $this->id, $this->extMin, $this->phpMin);
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
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    /**
     * @param bool $isInterface
     */
    public function setInterface(bool $isInterface): void
    {
        $this->isInterface = $isInterface;
    }

    /**
     * @return int
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * @param int $flags
     */
    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
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
     * @param Dependency[] $dependencies
     * @return Class_
     */
    public function addDependencies(array $dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $relationship = new ClassRelationship();
            $relationship->setDependency($dependency);
            $relationship->setClass($this);

            if (!$this->relationships->contains($relationship)) {
                $this->relationships->add($relationship);
            }
        }
        return $this;
    }

    /**
     * @return Dependency[]
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
