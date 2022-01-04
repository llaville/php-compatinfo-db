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

use Bartlett\CompatInfoDb\Application\Command\Build\BuildCommand;
use Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler;
use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandInterface;
use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseCommand;
use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler;

function dataSource(): Generator
{
    $classes = [
        BuildCommand::class,
        BuildHandler::class,
        ReleaseCommand::class,
        ReleaseHandler::class,
        CommandBusInterface::class,
        CommandHandlerInterface::class,
        CommandInterface::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
