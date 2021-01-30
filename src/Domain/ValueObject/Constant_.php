<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
final class Constant_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private $name;
    private $declaringClass;
    private $extension;
    /** @var array */
    private $dependencies;

    public function __construct(
        string $name,
        ?string $declaringClass,
        string $extension,
        string $extMin,
        string $extMax,
        string $phpMin,
        string $phpMax,
        iterable $dependencies
    ) {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
        $this->extension = $extension;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
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
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
