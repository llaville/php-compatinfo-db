<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;

use DateTimeImmutable;
use function sprintf;

/**
 * @since Release 3.0.0
 */
final class Platform
{
    /** @var string  */
    private $description;
    /** @var string  */
    private $version;
    /** @var DateTimeImmutable  */
    private $createdAt;
    /** @var Extension[]  */
    private $extensions;

    /**
     * Platform constructor.
     *
     * @param string $description
     * @param string $version
     * @param DateTimeImmutable $createdAt
     * @param Extension[] $extensions
     */
    public function __construct(
        string $description,
        string $version,
        DateTimeImmutable $createdAt,
        array $extensions
    ) {
        $this->description = $description;
        $this->version = $version;
        $this->createdAt = $createdAt;
        $this->extensions = $extensions;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Platform (desc: "%s", version: %s, built: %s) with %d extension%s',
            $this->description,
            $this->version,
            $this->createdAt->format('c'),
            count($this->extensions),
            count($this->extensions) > 1 ? 's' : ''
        );
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Extension[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
