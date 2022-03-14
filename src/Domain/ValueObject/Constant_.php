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
final class Constant_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    private string $name;
    private ?string $declaringClass;
    private string $extension;
    /** @var array|Dependency[] */
    private array $dependencies;
    private ?string $polyfill;

    /**
     * Constant_ constructor.
     *
     * @param string $name
     * @param string|null $declaringClass
     * @param string $extension
     * @param string $extMin
     * @param string|null $extMax
     * @param string $phpMin
     * @param string|null $phpMax
     * @param array|Dependency[] $dependencies
     * @param string|null $polyfill
     */
    public function __construct(
        string $name,
        ?string $declaringClass,
        string $extension,
        string $extMin,
        ?string $extMax,
        string $phpMin,
        ?string $phpMax,
        array $dependencies = [],
        ?string $polyfill = null
    ) {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
        $this->extension = $extension;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
        $this->dependencies = $dependencies;
        $this->polyfill = $polyfill;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

    /**
     * @return string|null
     */
    public function getPolyfill(): ?string
    {
        return $this->polyfill;
    }

    /**
     * @return string
     */
    public function getExtensionName(): string
    {
        return $this->extension;
    }

    /**
     * @return array|Dependency[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
