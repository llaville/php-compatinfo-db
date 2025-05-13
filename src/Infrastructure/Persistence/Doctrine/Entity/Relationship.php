<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, Table, ManyToOne, UniqueConstraint};

#[Entity]
#[Table(name: "relationships")]
#[UniqueConstraint(name: "platform_extension_unique", columns: ["platform_id", "extension_id"])]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Relationship
{
    use PrimaryIdentifierTrait;

    #[ManyToOne(targetEntity: Platform::class, cascade: ["persist"], inversedBy: "relationships")]
    private ?Platform $platform;

    #[ManyToOne(targetEntity: Extension::class, cascade: ["persist"])]
    private ?Extension $extension;

    public function setPlatform(Platform $platform): void
    {
        $this->platform = $platform;
    }

    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }

    public function getExtension(): Extension
    {
        return $this->extension;
    }
}
