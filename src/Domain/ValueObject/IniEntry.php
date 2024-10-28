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
final class IniEntry
{
    use ExtVersionTrait;
    use PhpVersionTrait;
    use DeprecationTrait;

    private string $name;
    /** @var Dependency[] */
    private array $dependencies;

    /**
     * IniEntry constructor.
     *
     * @param Dependency[] $dependencies
     */
    public function __construct(
        string $name,
        string $extMin,
        ?string $extMax,
        string $phpMin,
        ?string $phpMax,
        array $dependencies,
        ?Deprecated $deprecated
    ) {
        $this->name = $name;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
        $this->dependencies = $dependencies;
        $this->deprecated = $deprecated;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Dependency[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
