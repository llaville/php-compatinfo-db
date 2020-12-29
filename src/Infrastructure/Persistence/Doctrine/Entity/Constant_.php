<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, OneToMany, Table, Column, ManyToOne};

/**
 * @Entity
 * @Table(name="constants")
 * @since Release 3.0.0
 */
final class Constant_
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
     * @Column(name="declaring_class", type="string", nullable=true)
     * @var null|string
     */
    private $declaringClass;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="constants")
     * @var Extension
     */
    private $extension;

    /**
     * @OneToMany(targetEntity=ConstantRelationship::class, cascade={"persist"}, mappedBy="constant")
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
        return sprintf('Constant (id: %s, version: "%s %s")', $this->id, $this->extMin, $this->phpMin);
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
     * @return string|null
     */
    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

    /**
     * @param string|null $declaringClass
     */
    public function setDeclaringClass(?string $declaringClass): void
    {
        $this->declaringClass = $declaringClass;
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
     * @return Constant_
     */
    public function addDependencies(Collection $dependencies): self
    {
        /** @var Dependency $dependency */
        foreach ($dependencies as $dependency) {
            $relationship = new ConstantRelationship();
            $relationship->setDependency($dependency);
            $relationship->setConstant($this);

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
