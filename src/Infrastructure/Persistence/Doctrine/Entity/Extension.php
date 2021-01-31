<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, Table, Column, UniqueConstraint, OneToMany};

use function sprintf;
use function strtolower;

/**
 * @Entity
 * @Table(name="extensions",
 *    uniqueConstraints={@UniqueConstraint(name="extension_unique", columns={"name"})}
 * )
 * @since Release 3.0.0
 */
class Extension
{
    use PrimaryIdentifierTrait;

    /**
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @Column(type="string", length=16)
     * @var string
     */
    private $version;

    /**
     * @Column(type="string")
     * @var string
     */
    private $type;

    /**
     * @OneToMany(targetEntity=Release::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
     */
    private $releases;

    /**
     * @OneToMany(targetEntity=Dependency::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
     */
    private $dependencies;

    /**
     * @OneToMany(targetEntity=IniEntry::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
     */
    private $iniEntries;

    /**
     * @OneToMany(targetEntity=Constant_::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
     */
    private $constants;

    /**
     * @OneToMany(targetEntity=Function_::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
     */
    private $functions;

    /**
     * @OneToMany(targetEntity=Class_::class, cascade={"persist", "remove"}, mappedBy="extension")
     * @var Collection
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
     * @param Collection $releases
     */
    public function addReleases(Collection $releases): void
    {
        /** @var Release $release */
        foreach ($releases as $release) {
            $this->releases->add($release);
            $release->setExtension($this);
        }
    }

    /**
     * @return Collection
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    /**
     * @param Collection $configs
     */
    public function addIniEntries(Collection $configs): void
    {
        /** @var IniEntry $ini */
        foreach ($configs as $ini) {
            $this->iniEntries->add($ini);
            $ini->setExtension($this);
        }
    }

    /**
     * @return Collection
     */
    public function getIniEntries(): Collection
    {
        return $this->iniEntries;
    }

    /**
     * @param Collection $constants
     */
    public function addConstants(Collection $constants): void
    {
        /** @var IniEntry $constant */
        foreach ($constants as $constant) {
            $this->constants->add($constant);
            $constant->setExtension($this);
        }
    }

    /**
     * @return Collection
     */
    public function getConstants(): Collection
    {
        return $this->constants;
    }

    /**
     * @param Collection $functions
     */
    public function addFunctions(Collection $functions): void
    {
        /** @var Function_ $function */
        foreach ($functions as $function) {
            $this->functions->add($function);
            $function->setExtension($this);
        }
    }

    /**
     * @return Collection
     */
    public function getFunctions(): Collection
    {
        return $this->functions;
    }

    /**
     * @param Collection $classes
     */
    public function addClasses(Collection $classes): void
    {
        /** @var Class_ $class */
        foreach ($classes as $class) {
            $this->classes->add($class);
            $class->setExtension($this);
        }
    }

    /**
     * @return Collection
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    /**
     * @param Collection $dependencies
     * @return Extension
     */
    public function addDependencies(Collection $dependencies): self
    {
        /** @var Dependency $dependency */
        foreach ($dependencies as $dependency) {
            $this->dependencies->add($dependency);
            $dependency->setExtension($this);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getDependencies(): Collection
    {
        return $this->dependencies;
    }
}
