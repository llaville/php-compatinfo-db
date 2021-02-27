<?php declare(strict_types=1);

/**
 * Value Object of console db:init command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Init;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

/**
 * @since Release 2.0.0RC1
 */
final class InitQuery implements QueryInterface
{
    /** @var string  */
    private $appVersion;
    /** @var StyleInterface  */
    private $io;
    /** @var bool  */
    private $force;

    public function __construct(
        string $version,
        StyleInterface $io,
        bool $force
    ) {
        $this->appVersion = $version;
        $this->io = $io;
        $this->force = $force;
    }

    /**
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * @return StyleInterface
     */
    public function getStyle(): StyleInterface
    {
        return $this->io;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }
}
