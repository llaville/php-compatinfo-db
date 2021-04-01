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
    /** @var int */
    private $flags;

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
     * @param int $flags
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
        array $dependencies,
        int $flags
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
        $this->flags = $flags;
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

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_ABSTRACT);
    }

    /**
     * @return bool
     */
    public function isFinal(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_FINAL);
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_STATIC);
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PUBLIC);
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PROTECTED);
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PRIVATE);
    }
}
