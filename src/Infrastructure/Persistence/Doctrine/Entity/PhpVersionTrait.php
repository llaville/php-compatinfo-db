<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;

trait PhpVersionTrait
{
    /**
     * @Column(name="php_min", type="string", length=16)
     * @var string
     */
    private $phpMin;

    /**
     * @Column(name="php_max", type="string", length=16, nullable=true)
     * @var string
     */
    private $phpMax;

    /**
     * @return string
     */
    public function getPhpMin(): string
    {
        return $this->phpMin;
    }

    /**
     * @param string $phpMin
     */
    public function setPhpMin(string $phpMin): void
    {
        $this->phpMin = $phpMin;
    }

    /**
     * @return null|string
     */
    public function getPhpMax(): ?string
    {
        return $this->phpMax;
    }

    /**
     * @param null|string $phpMax
     */
    public function setPhpMax(?string $phpMax): void
    {
        $this->phpMax = $phpMax;
    }
}
