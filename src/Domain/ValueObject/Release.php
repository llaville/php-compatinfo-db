<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 */
final class Release
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    /** @var string  */
    private $version;
    /** @var DateTimeImmutable  */
    private $date;
    /** @var string  */
    private $state;

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

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
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
