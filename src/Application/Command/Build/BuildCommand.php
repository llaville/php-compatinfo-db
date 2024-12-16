<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bartlett\CompatInfoDb\Application\Command\Build;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Value Object of console db:build command.
 *
 * @since Release 3.5.0
 * @author Laurent Laville
 */
final class BuildCommand implements CommandInterface
{
    public function __construct(
        private readonly string $extension,
        private readonly string $extMin,
        private readonly string $phpMin,
        private readonly OutputInterface $output
    ) {
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getExtMin(): string
    {
        return $this->extMin;
    }

    public function getPhpMin(): string
    {
        return $this->phpMin;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
