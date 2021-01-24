<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

/**
 * @Entity
 * @Table(name="relationships",
 *    uniqueConstraints={@UniqueConstraint(name="platform_extension_unique", columns={"platform_id", "extension_id"})}
 * )
 * @since Release 3.0.0
 */
class Relationship
{
    use PrimaryIdentifierTrait;

    /**
     * @ManyToOne(targetEntity=Platform::class, cascade={"persist"}, inversedBy="relationships")
     * @var Platform
     */
    private $platform;

    /**
     * @ManyToOne(targetEntity=Extension::class, cascade={"persist"}, fetch="EAGER")
     * @var Extension
     */
    private $extension;

    /**
     * @param Platform $platform
     */
    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @param Extension $extension
     */
    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return Extension
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }
}
