<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Init;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

/**
 * Value Object of console db:init command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class InitCommand implements CommandInterface
{
    private string $appVersion;
    private StyleInterface $io;
    private bool $force;
    private bool $progress;
    private bool $installedOnly;
    private bool $distributionOnly;

    public function __construct(
        string $version,
        StyleInterface $io,
        bool $force,
        bool $progress,
        bool $installedOnly,
        bool $distributionOnly
    ) {
        $this->appVersion = $version;
        $this->io = $io;
        $this->force = $force;
        $this->progress = $progress;
        $this->installedOnly = $installedOnly;
        $this->distributionOnly = $distributionOnly;
    }

    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    public function getStyle(): StyleInterface
    {
        return $this->io;
    }

    public function isForce(): bool
    {
        return $this->force;
    }

    public function isProgress(): bool
    {
        return $this->progress;
    }

    public function isInstalledOnly(): bool
    {
        return $this->installedOnly;
    }

    public function isDistributionOnly(): bool
    {
        return $this->distributionOnly;
    }
}
