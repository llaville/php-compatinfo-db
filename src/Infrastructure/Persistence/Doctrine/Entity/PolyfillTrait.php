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
 * @since Release 6.12.0
 * @author Laurent Laville
 */
trait PolyfillTrait
{
    #[Column(name: "polyfill", type: "string", nullable: true)]
    private ?string $polyfill = null;


    public function getPolyfill(): ?string
    {
        return $this->polyfill;
    }

    public function setPolyfill(?string $polyfill): void
    {
        $this->polyfill = $polyfill;
    }
}
