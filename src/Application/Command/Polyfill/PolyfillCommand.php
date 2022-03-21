<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Polyfill;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

/**
 * Value Object of console db:polyfill command.
 *
 * @since Release 4.2.0
 * @author Laurent Laville
 */
final class PolyfillCommand implements CommandInterface
{
    private string $package;
    private string $tag;
    /** @var string[]  */
    private array $php;
    private StyleInterface $io;

    /**
     * @param string[] $php
     */
    public function __construct(string $package, string $tag, array $php, StyleInterface $io)
    {
        $this->package = $package;
        $this->tag = $tag;
        $this->php = $php;
        $this->io = $io;
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return string[]
     */
    public function getPhp(): array
    {
        return $this->php;
    }

    public function getStyle(): StyleInterface
    {
        return $this->io;
    }
}
