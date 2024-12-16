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
    public function __construct(
        private readonly string $extension,
        private readonly bool $releases,
        private readonly bool $ini,
        private readonly bool $constants,
        private readonly bool $functions,
        private readonly bool $interfaces,
        private readonly bool $classes,
        private readonly bool $methods,
        private readonly bool $classConstants,
        private readonly bool $dependencies,
        private readonly bool $polyfills,
        private readonly bool $deprecations
    ) {
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
