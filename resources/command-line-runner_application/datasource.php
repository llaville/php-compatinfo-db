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

use Bartlett\CompatInfoDb\Application\Command\Build\BuildCommand;
use Bartlett\CompatInfoDb\Application\Command\Create\CreateCommand;
use Bartlett\CompatInfoDb\Application\Command\Init\InitCommand;
use Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillCommand;
use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseCommand;
use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler;
use Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowHandler;

return function (): Generator {
    $classes = [
        BuildCommand::class,
        CreateCommand::class,
        InitCommand::class,
        PolyfillCommand::class,
        ReleaseCommand::class,

        DiagnoseHandler::class,
        ListHandler::class,
        ShowHandler::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
