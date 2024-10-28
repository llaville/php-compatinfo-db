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

#[Entity]
#[Table(name: "functions")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Function_
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;
    use PolyfillTrait;
    use DeprecatedElementTrait;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "simple_array", nullable: true)]
    private ?array $parameters; // @phpstan-ignore-line

    #[Column(type: "simple_array", nullable: true)]
    private ?array $excludes;   // @phpstan-ignore-line

    #[Column(name: "declaring_class", type: "string", nullable: true)]
    private ?string $declaringClass;

    #[Column(name: "prototype", type: "string", nullable: true)]
    private ?string $prototype;

    #[Column(name: "flags", type: "integer")]
    private int $flags;

    #[ManyToOne(targetEntity: Extension::class, inversedBy: "functions")]
    private Extension $extension;

    #[OneToMany(
        mappedBy: "function",
        targetEntity: FunctionRelationship::class,
        cascade: ["persist"]
    )]
    /**
     * @var Collection<int, FunctionRelationship> $relationships
     */
    private Collection $relationships;


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->declaringClass === null) {
            $name = $this->name;
        } else {
            $name = sprintf('%s::%s', $this->declaringClass, $this->name);
        }

        return sprintf(
            'Function (id: %s, extension: %s, name: %s, EXT version: %s, PHP version: %s, Deprecated: %s)',
            $this->id,
            $this->getExtension()->getName(),
            $name,
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

    /**
     * @return ?string[]
     * @phpstan-return  ?string[]
     */
    public function getParameters(): ?array
    {
        return array_map('trim', $this->parameters);
    }

    /**
     * @param ?string[] $parameters
     * @phpstan-param  ?string[] $parameters
     */
    public function setParameters(?array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return ?string[]
     * @phpstan-return ?string[]
     */
    public function getExcludes(): ?array
    {
        return array_map('trim', $this->excludes);
    }

    /**
     * @param ?string[] $excludes
     * @phpstan-param ?string[] $excludes
     */
    public function setExcludes(?array $excludes): void
    {
        $this->excludes = $excludes;
    }

    public function getPrototype(): ?string
    {
        return $this->prototype;
    }

    public function setPrototype(?string $prototype): void
    {
        $this->prototype = $prototype;
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
