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
use function count;
use function sprintf;

#[Entity]
#[Table(name: "platforms")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Platform
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[Column(type: "string")]
    private string $description;

    #[Column(type: "string", length: 16)]
    private string $version;

    #[Column(name: "created_at", type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    #[OneToMany(
        mappedBy: "platform",
        targetEntity: Relationship::class,
        cascade: ["persist"],
    )]
    /**
     * @var Collection<int, Relationship> $relationships
     */
    private Collection $relationships;  // @phpstan-ignore doctrine.associationType


    public function __construct()
    {
        $this->relationships = new ArrayCollection();
    }

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

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

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
