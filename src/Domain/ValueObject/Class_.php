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
final class Class_
{
    use ExtVersionTrait;
    use PhpVersionTrait;

    public const MODIFIER_PUBLIC    =  1;
    public const MODIFIER_PROTECTED =  2;
    public const MODIFIER_PRIVATE   =  4;
    public const MODIFIER_STATIC    =  8;
    public const MODIFIER_ABSTRACT  = 16;
    public const MODIFIER_FINAL     = 32;

    private string $name;
    private bool $isInterface;
    private string $extension;
    /** @var array|Dependency[]  */
    private array $dependencies;
    private int $flags;

    /**
     * Class_ constructor.
     *
     * @param string $name
     * @param bool $isInterface
     * @param string $extension
     * @param string $extMin
     * @param string|null $extMax
     * @param string $phpMin
     * @param string|null $phpMax
     * @param array|Dependency[] $dependencies
     * @param int $flags
     */
    public function __construct(
        string $name,
        bool $isInterface,
        string $extension,
        string $extMin,
        ?string $extMax,
        string $phpMin,
        ?string $phpMax,
        array $dependencies = [],
        int $flags = self::MODIFIER_PUBLIC
    ) {
        $this->name = $name;
        $this->isInterface = $isInterface;
        $this->extension = $extension;
        $this->extMin = $extMin;
        $this->extMax = $extMax;
        $this->phpMin = $phpMin;
        $this->phpMax = $phpMax;
        $this->dependencies = $dependencies;
        $this->flags = $flags;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->isInterface;
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

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return (bool) ($this->flags & self::MODIFIER_ABSTRACT);
    }

    /**
     * @return bool
     */
    public function isFinal(): bool
    {
        return (bool) ($this->flags & self::MODIFIER_FINAL);
    }
}
