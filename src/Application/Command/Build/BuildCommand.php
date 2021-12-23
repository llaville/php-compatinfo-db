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
    private string $extension;
    private string $extMin;
    private string $phpMin;
    private OutputInterface $output;

    public function __construct(string $extension, string $extMin, string $phpMin, OutputInterface $output)
    {
        $this->extension = $extension;
        $this->extMin = $extMin;
        $this->phpMin = $phpMin;
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getExtMin(): string
    {
        return $this->extMin;
    }

    /**
     * @return string
     */
    public function getPhpMin(): string
    {
        return $this->phpMin;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
