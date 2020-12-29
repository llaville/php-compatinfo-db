<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
final class IniEntry
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private $name;
    /** @var array */
    private $dependencies;

    public function __construct(
        string $name,
        string $extMin,
        string $extMax,
        string $phpMin,
        string $phpMax,
        iterable $dependencies
    ) {
        $this->name = $name;
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
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
