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
 * Use a baseline to adopt StructArmed gradually from codebase of version 6.34.0
 *
 * @link https://github.com/llaville/php-compatinfo-db/tree/6.34
 * @link https://boundwize.github.io/structarmed/baselines/
 * @link https://boundwize.github.io/structarmed/configuration/
 * @since Release 6.34.0
 * @author Laurent Laville
 */

return Architecture::define()
    ->import(__DIR__ . '/structarmed.dist.php')
    ->baseline(__DIR__ . '/structarmed-baseline.php')
;
