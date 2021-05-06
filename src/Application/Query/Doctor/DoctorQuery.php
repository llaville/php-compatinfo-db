<?php declare(strict_types=1);

/**
 * Value Object of console doctor command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Doctor;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

/**
 * @since Release 3.6.0
 */
final class DoctorQuery implements QueryInterface
{
    /** @var string[] */
    private $extensions;

    public function __construct(Platform $platform)
    {
        $this->extensions = [];
        foreach ($platform->getExtensions() as $extension) {
            $this->extensions[] = $extension->getName();
        }
    }

    /**
     * @return string[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
