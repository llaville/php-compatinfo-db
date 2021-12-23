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
trait ExtVersionTrait
{
    private string $extMin;
    private ?string $extMax;

    public function getExtMin(): string
    {
        return $this->extMin;
    }

    public function getExtMax(): ?string
    {
        return $this->extMax;
    }
}
