<?php

declare(strict_types=1);

/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Rector\Config\RectorConfig;

/**
 * Rector configuration file to downgrade code base to PHP 8.2
 *
 * @since Release 6.34
 * @author Laurent Laville
 */

$rootDirectory = dirname(__DIR__);

return RectorConfig::configure()
    ->withPaths([
        $rootDirectory . DIRECTORY_SEPARATOR . 'src',
        $rootDirectory . DIRECTORY_SEPARATOR . 'tests',
        $rootDirectory . DIRECTORY_SEPARATOR . 'examples',
        $rootDirectory . DIRECTORY_SEPARATOR . 'resources',
    ])
    ->withDowngradeSets(php82: true)
;
