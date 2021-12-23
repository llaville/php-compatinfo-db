<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class Dependency
{
    private string $name;
    private string $constraint;

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
