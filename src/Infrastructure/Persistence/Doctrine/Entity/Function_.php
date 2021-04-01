<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, OneToMany, Table, Column, ManyToOne};
use function array_map;

/**
 * @Entity
 * @Table(name="functions")
 * @since Release 3.0.0
 */
class Function_
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
     * @Column(type="simple_array", nullable=true)
     * @var null|string[]
     */
    private $parameters;

    /**
     * @Column(type="simple_array", nullable=true)
     * @var null|string[]
     */
    private $excludes;

    /**
     * @Column(name="declaring_class", type="string", nullable=true)
     * @var null|string
     */
    private $declaringClass;

    /**
     * @Column(name="prototype", type="string", nullable=true)
     * @var null|string
     */
    private $prototype;

    /**
     * @Column(name="flags", type="integer")
     * @var int
     */
    private $flags;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="functions")
     * @var Extension
     */
    private $extension;

    /**
     * @OneToMany(targetEntity=FunctionRelationship::class, cascade={"persist"}, mappedBy="function")
     * @var Collection<int, FunctionRelationship>
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
        return sprintf(
            'Function (id: %s, class: %s, version: "%s %s")',
            $this->id,
            $this->declaringClass,
            $this->extMin,
            $this->phpMin
        );
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
     * @return null|string[]
     */
    public function getParameters(): ?array
    {
        return array_map('trim', $this->parameters);
    }

    /**
     * @param null|string[] $parameters
     */
    public function setParameters(?array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return null|string[]
     */
    public function getExcludes(): ?array
    {
        return array_map('trim', $this->excludes);
    }

    /**
     * @param null|string[] $excludes
     */
    public function setExcludes(?array $excludes): void
    {
        $this->excludes = $excludes;
    }

    /**
     * @return string|null
     */
    public function getPrototype(): ?string
    {
        return $this->prototype;
    }

    /**
     * @param string|null $prototype
     */
    public function setPrototype(?string $prototype): void
    {
        $this->prototype = $prototype;
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
     * @return Function_
     */
    public function addDependencies(array $dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $relationship = new FunctionRelationship();
            $relationship->setDependency($dependency);
            $relationship->setFunction($this);

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
