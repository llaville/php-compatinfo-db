<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\{Entity, Table, Id, Column, GeneratedValue, OneToMany};

use DateTimeImmutable;

/**
 * @Entity
 * @Table(name="platforms")
 * @since Release 3.0.0
 */
final class Platform
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @Column(type="string", length=16)
     * @var string
     */
    private $version;

    /**
     * @Column(name="created_at", type="datetime_immutable")
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @OneToMany(targetEntity=Relationship::class, cascade={"persist"}, mappedBy="platform")
     * @var Collection
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
            $this->createdAt,
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
     * @param array $extensions
     * @return Platform
     */
    public function addExtensions(array $extensions): self
    {
        /** @var Extension $extension */
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
     * @return array
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
