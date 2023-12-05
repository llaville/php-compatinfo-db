<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;

use DateTimeImmutable;
use function count;
use function sprintf;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class Platform
{
    private string $description;
    private string $version;
    private DateTimeImmutable $createdAt;
    /** @var Extension[]  */
    private array $extensions;

    /**
     * Platform constructor.
     *
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

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
