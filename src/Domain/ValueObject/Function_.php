<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
final class Function_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private $name;
    private $declaringClass;
    private $extension;
    /** @var array|null */
    private $parameters;
    /** @var array|null */
    private $excludes;
    /** @var array */
    private $dependencies;

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
        iterable $dependencies
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
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @return array|null
     */
    public function getExcludes(): ?array
    {
        return $this->excludes;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
