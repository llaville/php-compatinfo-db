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

/**
 * @Entity
 * @Table(name="constants")
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Constant_
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;

    /**
     * @Column(type="string")
     */
    private string $name;

    /**
     * @Column(name="declaring_class", type="string", nullable=true)
     */
    private ?string $declaringClass;

    /**
     * @Column(name="polyfill", type="string", nullable=true)
     */
    private ?string $polyfill;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="constants")
     */
    private Extension $extension;

    /**
     * @OneToMany(targetEntity=ConstantRelationship::class, cascade={"persist"}, mappedBy="constant")
     * @var Collection<int, ConstantRelationship>
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
     * @return Constant_
     */
    public function addDependencies(array $dependencies): self
    {
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
