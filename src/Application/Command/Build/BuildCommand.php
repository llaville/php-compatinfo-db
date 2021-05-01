<?php declare(strict_types=1);

/**
 * Value Object of console db:build command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Command\Build;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @since Release 3.5.0
 */
final class BuildCommand implements CommandInterface
{
    /** @var string  */
    private $extension;
    /** @var string  */
    private $extMin;
    /** @var string  */
    private $phpMin;
    /** @var OutputInterface  */
    private $output;

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
