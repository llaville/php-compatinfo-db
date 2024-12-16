<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Release;

use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

/**
 * Value Object of console db:release command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ReleaseCommand implements CommandInterface
{
    public function __construct(
        private readonly string $version,
        private readonly string $date,
        private readonly string $state,
        private readonly string $extension
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }
}
