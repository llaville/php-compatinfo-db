<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Init;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

/**
 * Value Object of console db:init command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class InitQuery implements QueryInterface
{
    private string $appVersion;
    private StyleInterface $io;
    private bool $force;
    private bool $progress;

    public function __construct(
        string $version,
        StyleInterface $io,
        bool $force,
        bool $progress
    ) {
        $this->appVersion = $version;
        $this->io = $io;
        $this->force = $force;
        $this->progress = $progress;
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

    /**
     * @return bool
     */
    public function isProgress(): bool
    {
        return $this->progress;
    }
}
