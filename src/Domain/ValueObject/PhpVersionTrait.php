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
trait PhpVersionTrait
{
    private string $phpMin;
    private ?string $phpMax;

    public function getPhpMin(): string
    {
        return $this->phpMin;
    }

    public function getPhpMax(): ?string
    {
        return $this->phpMax;
    }
}
