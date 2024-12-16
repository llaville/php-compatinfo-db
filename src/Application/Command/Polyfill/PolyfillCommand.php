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
    /**
     * @param string[] $php
     */
    public function __construct(
        private readonly string $package,
        private readonly string $tag,
        private readonly array $php,
        private readonly StyleInterface $io,
        private readonly string $cacheDir,
        private readonly ?string $whitelist
    ) {
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

    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    public function getWhitelist(): ?string
    {
        return $this->whitelist;
    }
}
