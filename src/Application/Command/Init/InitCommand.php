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
    public function __construct(
        private readonly string $appVersion,
        private readonly StyleInterface $io,
        private readonly bool $force,
        private readonly bool $progress
    ) {
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
}
