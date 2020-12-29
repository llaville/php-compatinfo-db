<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
trait ExtVersionTrait
{
    private $extMin;
    private $extMax;

    public function getExtMin(): string
    {
        return $this->extMin;
    }

    public function getExtMax(): string
    {
        return $this->extMax;
    }
}
