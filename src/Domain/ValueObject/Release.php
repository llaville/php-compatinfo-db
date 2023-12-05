<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class Release
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private string $version;
    private DateTimeImmutable $date;
    private string $state;

    public function __construct(
        string $version,
        DateTimeImmutable $date,
        string $state,
        string $extMin,
        ?string $extMax,
        string $phpMin,
        ?string $phpMax
    ) {
        $this->version = $version;
        $this->date = $date;
        $this->state = $state;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getState(): string
    {
        return $this->state;
    }

    /**
     * **CAUTION** Just to have an API consistent for ShowCommand
     *
     * @return list<int>
     */
    public function getDependencies(): array
    {
        return [];
    }
}
