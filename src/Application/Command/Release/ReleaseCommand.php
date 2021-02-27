<?php declare(strict_types=1);

/**
 * Value Object of console db:release command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Command\Release;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

/**
 * @since Release 2.0.0RC1
 */
final class ReleaseCommand implements CommandInterface
{
    /** @var string  */
    private $version;
    /** @var string  */
    private $date;
    /** @var string  */
    private $state;

    public function __construct(
        string $version,
        string $date,
        string $state
    ) {
        $this->version = $version;
        $this->date = $date;
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
