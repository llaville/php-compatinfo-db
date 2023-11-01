<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, OneToMany, Table, Column, ManyToOne};

use function array_map;
use function sprintf;

/**
 * @Entity
 * @Table(name="functions")
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Function_
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;

    /**
     * @Column(type="string")
     */
    private string $name;

    /**
     * @Column(type="simple_array", nullable=true)
     * @var null|string[]
     */
    private ?array $parameters;

    /**
     * @Column(type="simple_array", nullable=true)
     * @var null|string[]
     */
    private ?array $excludes;

    /**
     * @Column(name="declaring_class", type="string", nullable=true)
     */
    private ?string $declaringClass;

    /**
     * @Column(name="prototype", type="string", nullable=true)
     */
    private ?string $prototype;

    /**
     * @Column(name="flags", type="integer")
     */
    private int $flags;

    /**
     * @Column(name="polyfill", type="string", nullable=true)
     */
    private ?string $polyfill;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="functions")
     */
    private Extension $extension;

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
        if ($this->declaringClass === null) {
            $name = $this->name;
        } else {
            $name = sprintf('%s::%s', $this->declaringClass, $this->name);
        }

        return sprintf(
            'Function (id: %s, extension: %s, name: %s, EXT version: %s, PHP version: %s)',
            $this->id,
            $this->getExtension()->getName(),
            $name,
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
     * @return string|null
     */
    public function getPolyfill(): ?string
    {
        return $this->polyfill;
    }

    /**
     * @param string|null $polyfill
     */
    public function setPolyfill(?string $polyfill): void
    {
        $this->polyfill = $polyfill;
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
