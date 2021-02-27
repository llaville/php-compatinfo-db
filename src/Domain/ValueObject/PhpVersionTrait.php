<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
trait PhpVersionTrait
{
    /** @var string */
    private $phpMin;
    /** @var string|null */
    private $phpMax;

    public function getPhpMin(): string
    {
        return $this->phpMin;
    }

    public function getPhpMax(): ?string
    {
        return $this->phpMax;
    }
}
