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

use function sprintf;

#[Entity]
#[Table(name: "constants")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Constant_
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;
    use PolyfillTrait;
    use DeprecatedElementTrait;

    #[Column(type: "string")]
    private string $name;

    #[Column(name: "declaring_class", type: "string", nullable: true)]
    private ?string $declaringClass;

    #[ManyToOne(targetEntity: Extension::class, inversedBy: "constants")]
    private Extension $extension;

    #[OneToMany(
        mappedBy: "constant",
        targetEntity: ConstantRelationship::class,
        cascade: ["persist"]
    )]
    /**
     * @var Collection<int, ConstantRelationship> $relationships
     */
    private Collection $relationships;


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf(
            'Constant (id: %s, extension: %s, name: %s, EXT version: %s, PHP version: %s, Deprecated: %s)',
            $this->id,
            $this->getExtension()->getName(),
            $this->name,
            $this->extMin,
            $this->phpMin,
            $this->getDeprecated() === null ? 'N' : 'Y',
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

    public function setDeclaringClass(?string $declaringClass): void
    {
        $this->declaringClass = $declaringClass;
    }

    public function getExtension(): Extension
    {
        return $this->extension;
    }

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
