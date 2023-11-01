<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, Column, ManyToOne};

use DateTimeImmutable;
use function sprintf;

/**
 * @Entity
 * @Table(name="releases")
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Release
{
    use PrimaryIdentifierTrait;
    use ExtVersionTrait;
    use PhpVersionTrait;

    /**
     * @Column(type="string", length=16)
     */
    private string $version;

    /**
     * @Column(type="date_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @Column(type="string")
     */
    private string $state;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="releases")
     */
    private Extension $extension;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Release (id: %s, version: %s, date: %s, state: %s)',
            $this->id,
            $this->version,
            $this->date->format('c'),
            $this->state
        );
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     */
    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @param Extension $extension
     */
    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }
}
