<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use Deprecated;
use function sprintf;

/**
 * @since Release 6.12.0
 * @author Laurent Laville
 */
trait DeprecationTrait
{
    private ?Deprecated $deprecated;

    public function getDeprecated(): ?string
    {
        if ($this->deprecated === null) {
            return null;
        }

        $deprecated = 'is deprecated';
        if ($this->deprecated->since !== null) {
            $deprecated .= sprintf(
                ' since PHP %s',
                $this->deprecated->since
            );
        }
        if ($this->deprecated->message !== null) {
            $deprecated .= ' ' . $this->deprecated->message;
        }
        return $deprecated;
    }
}
