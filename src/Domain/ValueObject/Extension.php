<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;

use function sprintf;
use function strtolower;
use const PHP_VERSION;

/**
 * @since Release 3.0.0
 */
final class Extension implements ExtensionVersionProviderInterface
{
    /** @var string  */
    private $name;
    /** @var string  */
    private $description;
    /** @var string  */
    private $version;
    /** @var string  */
    private $type;
    /** @var array|Dependency[]  */
    private $dependencies;
    /** @var array|IniEntry[] */
    private $iniEntries;
    /** @var array|Constant_[]  */
    private $constants;
    /** @var array|Function_[]  */
    private $functions;
    /** @var array|Class_[] */
    private $classes;
    /** @var array|Release[]  */
    private $releases;
    /** @var array|Class_[]  */
    private $interfaces;
    /** @var array|Function_[] */
    private $methods;
    /** @var array|Constant_[] */
    private $classConstants;

    /**
     * Extension constructor.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param array|IniEntry[] $iniEntries
     * @param array|Constant_[] $constants
     * @param array|Function_[] $functions
     * @param array|Class_[] $classes
     * @param array|Dependency[] $dependencies
     * @param array|Release[] $releases
     */
    public function __construct(
        string $name,
        string $version = PHP_VERSION,
        string $type = 'bundle',
        array $iniEntries = [],
        array $constants = [],
        array $functions = [],
        array $classes = [],
        array $dependencies = [],
        array $releases = []
    ) {
        $this->name = strtolower($name);
        $this->description = sprintf('The %s PHP extension', $this->name);
        $this->version = $version;
        $this->type = strtolower($type);
        $this->releases = $releases;
        $this->dependencies = $dependencies;
        $this->iniEntries = $iniEntries;

        $this->constants = $this->classConstants = [];
        foreach ($constants as $name => $domain) {
            if (empty($domain->getDeclaringClass())) {
                $this->constants[$name] = $domain;
            } else {
                $this->classConstants[$name] = $domain;
            }
            foreach ($domain->getDependencies() as $dependency) {
                $this->dependencies[] = $dependency;
            }
        }

        $this->functions = $this->methods = [];
        foreach ($functions as $name => $domain) {
            if (empty($domain->getDeclaringClass())) {
                $this->functions[$name] = $domain;
            } else {
                $this->methods[$name] = $domain;
            }
            foreach ($domain->getDependencies() as $dependency) {
                $this->dependencies[] = $dependency;
            }
        }

        $this->classes = $this->interfaces = [];
        foreach ($classes as $name => $domain) {
            if ($domain->isInterface()) {
                $this->interfaces[$name] = $domain;
            } else {
                $this->classes[$name] = $domain;
            }
            foreach ($domain->getDependencies() as $dependency) {
                $this->dependencies[] = $dependency;
            }
        }
    }

    public function __toString(): string
    {
        return sprintf(
            'Extension (name: %s, version: %s, type: %s) with %d releases, %d ini, %d constants, %d functions, %d classes',
            $this->name,
            $this->version,
            $this->type,
            count($this->releases),
            count($this->iniEntries),
            count($this->constants),
            count($this->functions),
            count($this->classes)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'type' => $this->type,
            'dependencies' => $this->dependencies,
            'ini_entries' => $this->iniEntries,
            'constants' => $this->constants,
            'functions' => $this->functions,
            'classes' => $this->classes,
            'interfaces' => $this->interfaces,
            'class_constants' => $this->classConstants,
            'methods' => $this->methods,
            'releases' => $this->releases,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|Dependency[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return array|IniEntry[]
     */
    public function getIniEntries(): array
    {
        return $this->iniEntries;
    }

    /**
     * @return array|Constant_[]
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @return array|Function_[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * @return array|Class_[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return array|Release[]
     */
    public function getReleases(): array
    {
        return $this->releases;
    }

    /**
     * @return Release
     */
    public function getLastRelease(): Release
    {
        return end($this->releases);
    }

    /**
     * @return array|Class_[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @return array|Function_[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return array|Constant_[]
     */
    public function getClassConstants(): array
    {
        return $this->classConstants;
    }
}
