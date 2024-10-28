<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\ValueObject;

use Deprecated;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class Constant_
{
    use ExtVersionTrait;
    use PhpVersionTrait;
    use DeprecationTrait;

    private string $name;
    private ?string $declaringClass;
    private string $extension;
    /** @var array|Dependency[] */
    private array $dependencies;
    private ?string $polyfill;

    /**
     * Constant_ constructor.
     *
     * @param array|Dependency[] $dependencies
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
        ?string $polyfill = null,
        ?Deprecated $deprecated = null
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
        $this->deprecated = $deprecated;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeclaringClass(): ?string
    {
        return $this->declaringClass;
    }

    public function getPolyfill(): ?string
    {
        return $this->polyfill;
    }

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
