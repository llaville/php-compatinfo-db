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

use Bartlett\CompatInfoDb\Presentation\Console\Application;
use Bartlett\CompatInfoDb\Presentation\Console\Command\AboutCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\BuildCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CreateCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\InitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ListCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\PolyfillCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ShowCommand;

return function (): Generator {
    $classes = [
        Application::class,
        AboutCommand::class,
        BuildCommand::class,
        CreateCommand::class,
        DiagnoseCommand::class,
        InitCommand::class,
        ListCommand::class,
        PolyfillCommand::class,
        ReleaseCommand::class,
        ShowCommand::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
};
