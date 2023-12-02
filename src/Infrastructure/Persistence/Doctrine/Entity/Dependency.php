<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, ManyToOne, Table, Column};

#[Entity]
#[Table(name: "dependencies")]
/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class Dependency
{
    use PrimaryIdentifierTrait;

    #[Column(type: "string")]
    private string $name;

    #[Column(name: "constraint_expression", type: "string", length: 16)]
    private string $constraintExpression;

    #[ManyToOne(targetEntity: Extension::class, inversedBy: "dependencies")]
    private Extension $extension;

    public function __toString(): string
    {
        return sprintf(
            'Dependency (id: %s, name: %s, constraint: "%s")',
            $this->id,
            $this->name,
            $this->constraintExpression
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getConstraintExpression(): string
    {
        return $this->constraintExpression;
    }

    public function setConstraintExpression(string $constraintExpression): void
    {
        $this->constraintExpression = $constraintExpression;
    }

    public function getExtension(): Extension
    {
        return $this->extension;
    }

    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }
}
