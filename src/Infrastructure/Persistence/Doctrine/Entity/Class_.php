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
#[Table(name: "classes")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Class_
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;
    use PolyfillTrait;
    use DeprecatedElementTrait;

    #[Column(type: "string")]
    private string $name;

    #[Column(name: "interface", type: "boolean")]
    private bool $isInterface;

    #[Column(name: "flags", type: "integer")]
    private int $flags;

    #[ManyToOne(targetEntity: Extension::class, inversedBy: "classes")]
    private ?Extension $extension;

    #[OneToMany(
        mappedBy: "class",
        targetEntity: ClassRelationship::class,
        cascade: ["persist"]
    )]
    /**
     * @var Collection<int, ClassRelationship> $relationships
     */
    private Collection $relationships;  // @phpstan-ignore doctrine.associationType


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf(
            'Class (id: %s, extension: %s, name: %s, EXT version: %s, PHP version: %s, Deprecated: %s)',
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

    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    public function setInterface(bool $isInterface): void
    {
        $this->isInterface = $isInterface;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function setFlags(int $flags): void
    {
        $this->flags = $flags;
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
