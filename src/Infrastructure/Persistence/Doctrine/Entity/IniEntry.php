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
#[Table(name: "ini_entries")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class IniEntry
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;
    use DeprecatedElementTrait;

    #[Column(type: "string")]
    private string $name;

    #[ManyToOne(targetEntity: Extension::class, inversedBy: "iniEntries")]
    private Extension $extension;

    #[OneToMany(
        mappedBy: "ini",
        targetEntity: IniRelationship::class,
        cascade: ["persist"],
    )]
    /**
     * @var Collection<int, IniRelationship> $relationships
     */
    private Collection $relationships;


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf(
            'IniEntry (id: %s, extension: %s, name: %s, EXT version: %s, PHP version: %s, Deprecated: %s)',
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
     * @return IniEntry
     */
    public function addDependencies(array $dependencies): self
    {
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
