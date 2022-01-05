<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, Table, Id, Column, GeneratedValue, OneToMany};

use DateTimeImmutable;

/**
 * @Entity
 * @Table(name="platforms")
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Platform
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Column(type="string")
     */
    private string $description;

    /**
     * @Column(type="string", length=16)
     */
    private string $version;

    /**
     * @Column(name="created_at", type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @OneToMany(targetEntity=Relationship::class, cascade={"persist"}, mappedBy="platform")
     * @var Collection<int, Relationship>
     */
    private $relationships;


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Platform (id: %s, desc: "%s", version: %s, built: %s) with %d reference%s',
            $this->id,
            $this->description,
            $this->version,
            $this->createdAt->format('c'),
            count($this->relationships),
            count($this->relationships) > 1 ? 's' : ''
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param Extension[] $extensions
     * @return Platform
     */
    public function addExtensions(array $extensions): self
    {
        foreach ($extensions as $extension) {
            $relationship = new Relationship();
            $relationship->setExtension($extension);
            $relationship->setPlatform($this);

            if (!$this->relationships->contains($relationship)) {
                $this->relationships->add($relationship);
            }
        }
        return $this;
    }

    /**
     * @return Extension[]
     */
    public function getExtensions(): array
    {
        $extensions = [];
        foreach ($this->relationships as $relationship) {
            $extensions[] = $relationship->getExtension();
        }
        return $extensions;
    }
}
