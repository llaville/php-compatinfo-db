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
trait ExtVersionTrait
{
    #[Column(name:"ext_min", type:"string", length:16)]
    private string $extMin;

    #[Column(name:"ext_max", type:"string", length:16, nullable:true)]
    private ?string $extMax;

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
