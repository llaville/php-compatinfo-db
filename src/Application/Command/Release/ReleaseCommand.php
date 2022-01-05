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
    private string $version;
    private string $date;
    private string $state;

    public function __construct(
        string $version,
        string $date,
        string $state
    ) {
        $this->version = $version;
        $this->date = $date;
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
