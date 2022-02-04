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

use Bartlett\CompatInfoDb\Presentation\Console\Command\BuildCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CreateCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DoctorCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\InitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ListCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ShowCommand;

function dataSource(): Generator
{
    $classes = [
        BuildCommand::class,
        CreateCommand::class,
        DiagnoseCommand::class,
        DoctorCommand::class,
        InitCommand::class,
        ListCommand::class,
        ReleaseCommand::class,
        ShowCommand::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
