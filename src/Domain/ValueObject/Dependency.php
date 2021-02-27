<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 */
final class Dependency
{
    /** @var string  */
    private $name;
    /** @var string  */
    private $constraint;

    public function __construct(
        string $name,
        string $constraint
    ) {
        $this->name = $name;
        $this->constraint = $constraint;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getConstraint(): string
    {
        return $this->constraint;
    }
}
