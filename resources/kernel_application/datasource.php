<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 6.1.0
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;

return function (): Generator {
    $classes = [
        ConsoleKernel::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
