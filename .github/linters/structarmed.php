<?php

declare(strict_types=1);

/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Boundwize\StructArmed\Architecture;

/**
 * StructArmed Configuration File for development
 *
 * @link https://boundwize.github.io/structarmed/configuration/
 * @since Release 6.34.0
 * @author Laurent Laville
 */

$architecture = Architecture::define()
    ->import(__DIR__ . '/structarmed.dist.php')
;
// remove if we have detected and included a `structarmed-baseline.dist.php` file
$architecture->baseline(null);

/**
 * Use a baseline to adopt StructArmed gradually from codebase of version 6.34.0
 *
 * @link https://github.com/llaville/php-compatinfo-db/tree/6.34
 * @link https://boundwize.github.io/structarmed/baselines/
 */

$baselineFile = __DIR__ . '/structarmed-baseline.php';

/**
 * Because the behavior is still blocking with version 0.21.28
 * @link https://github.com/boundwize/structarmed/issues/597
 * @TODO removed when version 0.22 will be available
 */

if (file_exists($baselineFile)) {
    $architecture->baseline($baselineFile);
}

return $architecture;
