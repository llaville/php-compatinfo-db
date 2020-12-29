<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;

trait ExtVersionTrait
{
    /**
     * @Column(name="ext_min", type="string", length=16)
     * @var string
     */
    private $extMin;

    /**
     * @Column(name="ext_max", type="string", length=16, nullable=true)
     * @var string
     */
    private $extMax;

    /**
     * @return string
     */
    public function getExtMin(): string
    {
        return $this->extMin;
    }

    /**
     * @param string $extMin
     */
    public function setExtMin(string $extMin): void
    {
        $this->extMin = $extMin;
    }

    /**
     * @return null|string
     */
    public function getExtMax(): ?string
    {
        return $this->extMax;
    }

    /**
     * @param null|string $extMax
     */
    public function setExtMax(?string $extMax): void
    {
        $this->extMax = $extMax;
    }
}
