<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Show;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

/**
 * Value Object of console db:show command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ShowQuery implements QueryInterface
{
    private string $extension;
    private bool $releases;
    private bool $ini;
    private bool $constants;
    private bool $functions;
    private bool $interfaces;
    private bool $classes;
    private bool $methods;
    private bool $classConstants;
    private bool $dependencies;
    private bool $polyfills;
    private bool $deprecations;

    public function __construct(
        string $extension,
        bool $releases,
        bool $ini,
        bool $constants,
        bool $functions,
        bool $interfaces,
        bool $classes,
        bool $methods,
        bool $classConstants,
        bool $dependencies,
        bool $polyfills,
        bool $deprecations
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
        $this->dependencies   = $dependencies;
        $this->polyfills      = $polyfills;
        $this->deprecations   = $deprecations;
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

    public function isDependencies(): bool
    {
        return $this->dependencies;
    }

    public function isPolyfills(): bool
    {
        return $this->polyfills;
    }

    public function isDeprecations(): bool
    {
        return $this->deprecations;
    }
}
