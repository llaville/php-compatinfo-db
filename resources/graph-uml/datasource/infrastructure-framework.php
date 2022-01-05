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

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\ContainerFactory;
use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\Polyfill;

function dataSource(): Generator
{
    $classes = [
        ContainerFactory::class,
        Polyfill::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
