<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\{Entity, ManyToOne, Table, Column};

/**
 * @Entity
 * @Table(name="dependencies")
 * @since Release 3.0.0
 */
final class Dependency
{
    use PrimaryIdentifierTrait;

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @Column(name="constraint_expression", type="string", length=16)
     * @var string
     */
    private $constraintExpression;

    /**
     * @ManyToOne(targetEntity=Extension::class, inversedBy="dependencies")
     * @var Extension
     */
    private $extension;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Dependency (id: %s, name: %s, constraint: "%s")',
            $this->id,
            $this->name,
            $this->constraintExpression
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getConstraintExpression(): string
    {
        return $this->constraintExpression;
    }

    /**
     * @param string $constraintExpression
     */
    public function setConstraintExpression(string $constraintExpression): void
    {
        $this->constraintExpression = $constraintExpression;
    }

    /**
     * @return Extension
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }

    /**
     * @param Extension $extension
     */
    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }
}
