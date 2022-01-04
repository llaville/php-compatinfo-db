<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 3.17.0
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;
use Bartlett\CompatInfoDb\Domain\ValueObject\ExtVersionTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;
use Bartlett\CompatInfoDb\Domain\ValueObject\IniEntry;
use Bartlett\CompatInfoDb\Domain\ValueObject\PhpVersionTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Domain\ValueObject\Release;

function dataSource(): Generator
{
    $classes = [
        Class_::class,
        Constant_::class,
        Dependency::class,
        Extension::class,
        ExtVersionTrait::class,
        Function_::class,
        IniEntry::class,
        PhpVersionTrait::class,
        Platform::class,
        Release::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
