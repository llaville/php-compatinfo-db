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

use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;

function dataSource(): Generator
{
    $classes = [
        ExtensionFactory::class,
        ExtensionVersionProviderInterface::class,
        ExtensionVersionProviderTrait::class,
        LibraryVersionProviderTrait::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
