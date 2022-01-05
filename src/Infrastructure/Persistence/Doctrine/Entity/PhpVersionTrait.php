<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
trait PhpVersionTrait
{
    /**
     * @Column(name="php_min", type="string", length=16)
     */
    private string $phpMin;

    /**
     * @Column(name="php_max", type="string", length=16, nullable=true)
     */
    private ?string $phpMax;

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
