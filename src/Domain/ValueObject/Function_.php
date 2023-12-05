<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class Function_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private string $name;
    private ?string $declaringClass;
    private string $extension;
    /** @var string[]|null */
    private ?array $parameters;
    /** @var string[]|null */
    private ?array $excludes;
    /** @var Dependency[] */
    private array $dependencies;
    private int $flags;
    private ?string $polyfill;

    /**
     * Function_ constructor.
     *
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
        array $dependencies,
        int $flags,
        ?string $polyfill
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
        $this->polyfill = $polyfill;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

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

    public function isAbstract(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_ABSTRACT);
    }

    public function isFinal(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_FINAL);
    }

    public function isStatic(): bool
    {
        return (bool) (!empty($this->declaringClass) && $this->flags & Class_::MODIFIER_STATIC);
    }

    public function isPublic(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PUBLIC);
    }

    public function isProtected(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PROTECTED);
    }

    public function isPrivate(): bool
    {
        return (bool) ($this->flags & Class_::MODIFIER_PRIVATE);
    }

    public function getPolyfill(): ?string
    {
        return $this->polyfill;
    }
}
