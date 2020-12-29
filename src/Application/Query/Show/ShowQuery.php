<?php declare(strict_types=1);

/**
 * Value Object of console db:show command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Show;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

/**
 * @since Release 2.0.0RC1
 */
final class ShowQuery implements QueryInterface
{
    private $extension;
    private $releases;
    private $ini;
    private $constants;
    private $functions;
    private $interfaces;
    private $classes;
    private $methods;
    private $classConstants;

    public function __construct(
        string $extension,
        bool $releases,
        bool $ini,
        bool $constants,
        bool $functions,
        bool $interfaces,
        bool $classes,
        bool $methods,
        bool $classConstants
    ) {
        $this->extension      = $extension;
        $this->releases       = $releases;
        $this->ini            = $ini;
        $this->constants      = $constants;
        $this->functions      = $functions;
        $this->interfaces     = $interfaces;
        $this->classes        = $classes;
        $this->methods        = $methods;
        $this->classConstants = $classConstants;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function isReleases(): bool
    {
        return $this->releases;
    }

    public function isIni(): bool
    {
        return $this->ini;
    }

    public function isConstants(): bool
    {
        return $this->constants;
    }

    public function isFunctions(): bool
    {
        return $this->functions;
    }

    public function isInterfaces(): bool
    {
        return $this->interfaces;
    }

    public function isClasses(): bool
    {
        return $this->classes;
    }

    public function isMethods(): bool
    {
        return $this->methods;
    }

    public function isClassConstants(): bool
    {
        return $this->classConstants;
    }
}
