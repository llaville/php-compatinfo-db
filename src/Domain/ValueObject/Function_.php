<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
final class Function_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    /** @var string  */
    private $name;
    /** @var string|null  */
    private $declaringClass;
    /** @var string  */
    private $extension;
    /** @var string[]|null */
    private $parameters;
    /** @var string[]|null */
    private $excludes;
    /** @var Dependency[] */
    private $dependencies;

    /**
     * Function_ constructor.
     *
     * @param string $name
     * @param string|null $declaringClass
     * @param string $extension
     * @param string $extMin
     * @param string|null $extMax
     * @param string $phpMin
     * @param string|null $phpMax
     * @param string[]|null $parameters
     * @param string[]|null $excludes
     * @param Dependency[] $dependencies
     */
    public function __construct(
        string $name,
        ?string $declaringClass,
        string $extension,
        string $extMin,
        ?string $extMax,
        string $phpMin,
        ?string $phpMax,
        ?array $parameters,
        ?array $excludes,
        array $dependencies
    ) {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
        $this->extension = $extension;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
        $this->parameters = $parameters;
        $this->excludes = $excludes;
        $this->dependencies = $dependencies;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

    /**
     * @return string
     */
    public function getExtensionName(): string
    {
        return $this->extension;
    }

    /**
     * @return string[]|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @return string[]|null
     */
    public function getExcludes(): ?array
    {
        return $this->excludes;
    }

    /**
     * @return Dependency[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
