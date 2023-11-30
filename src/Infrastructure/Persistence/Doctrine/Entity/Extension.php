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
use Doctrine\ORM\Mapping\{Entity, Table, Column, UniqueConstraint, OneToMany};

use function sprintf;
use function strtolower;

#[Entity]
#[Table(name: "extensions")]
#[UniqueConstraint(name: "extension_unique", columns: ["name"])]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Extension
{
    use PrimaryIdentifierTrait;

    #[Column(type: "string")]
    private string $description;

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "string", length: 16)]
    private string $version;

    #[Column(type: "string")]
    private string $type;

    #[Column(type: "boolean")]
    private bool $deprecated;

    #[OneToMany(
        mappedBy: "extension",
        targetEntity: Release::class,
        cascade: ["persist", "remove"]
    )]
    /**
     * @var Collection<int, Release>
     */
    private $releases;

    #[OneToMany(
        mappedBy: "extension",
        targetEntity: Dependency::class,
        cascade: ["persist", "remove"]
    )]
    /**
     * @var Collection<int, Dependency>
     */
    private $dependencies;

    #[OneToMany(
        mappedBy: "extension",
        targetEntity :IniEntry::class,
        cascade: ["persist", "remove"]
    )]
    /**
     * @var Collection<int, IniEntry>
     */
    private $iniEntries;

    #[OneToMany(
        mappedBy:"extension",
        targetEntity:Constant_::class,
        cascade:["persist", "remove"]
    )]
    /**
     * @var Collection<int, Constant_>
     */
    private $constants;

    #[OneToMany(
        mappedBy: "extension",
        targetEntity: Function_::class,
        cascade: ["persist", "remove"]
    )]
    /**
     * @var Collection<int, Function_>
     */
    private $functions;

    #[OneToMany(
        mappedBy: "extension",
        targetEntity: Class_::class,
        cascade: ["persist", "remove"]
    )]
    /**
     * @var Collection<int, Class_>
     */
    private $classes;


    public function __construct()
    {
        $this->releases = new ArrayCollection();
        $this->dependencies = new ArrayCollection();
        $this->iniEntries = new ArrayCollection();
        $this->constants = new ArrayCollection();
        $this->functions = new ArrayCollection();
        $this->classes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('Extension (id: %s, desc: "%s", version: %s)', $this->id, $this->description, $this->version);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = strtolower($name);
        $this->description = sprintf('The %s PHP extension', $name);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated
     */
    public function setDeprecated(bool $deprecated): void
    {
        $this->deprecated = $deprecated;
    }

    /**
     * @param Release[] $releases
     */
    public function addReleases(array $releases): void
    {
        foreach ($releases as $release) {
            $this->releases->add($release);
            $release->setExtension($this);
        }
    }

    /**
     * @return Collection<int, Release>
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    /**
     * @param IniEntry[] $configs
     */
    public function addIniEntries(array $configs): void
    {
        foreach ($configs as $ini) {
            $this->iniEntries->add($ini);
            $ini->setExtension($this);
        }
    }

    /**
     * @return Collection<int, IniEntry>
     */
    public function getIniEntries(): Collection
    {
        return $this->iniEntries;
    }

    /**
     * @param Constant_[] $constants
     */
    public function addConstants(array $constants): void
    {
        foreach ($constants as $constant) {
            $this->constants->add($constant);
            $constant->setExtension($this);
        }
    }

    /**
     * @return Collection<int, Constant_>
     */
    public function getConstants(): Collection
    {
        return $this->constants;
    }

    /**
     * @param Function_[] $functions
     */
    public function addFunctions(array $functions): void
    {
        foreach ($functions as $function) {
            $this->functions->add($function);
            $function->setExtension($this);
        }
    }

    /**
     * @return Collection<int, Function_>
     */
    public function getFunctions(): Collection
    {
        return $this->functions;
    }

    /**
     * @param Class_[] $classes
     */
    public function addClasses(array $classes): void
    {
        foreach ($classes as $class) {
            $this->classes->add($class);
            $class->setExtension($this);
        }
    }

    /**
     * @return Collection<int, Class_>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    /**
     * @param Dependency[] $dependencies
     * @return Extension
     */
    public function addDependencies(array $dependencies): self
    {
        foreach ($dependencies as $dependency) {
            $this->dependencies->add($dependency);
            $dependency->setExtension($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Dependency>
     */
    public function getDependencies(): Collection
    {
        return $this->dependencies;
    }
}
