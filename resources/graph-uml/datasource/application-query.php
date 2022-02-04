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

use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler;
use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseQuery;
use Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler;
use Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorQuery;
use Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler;
use Bartlett\CompatInfoDb\Application\Query\ListRef\ListQuery;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryInterface;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowHandler;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;

function dataSource(): Generator
{
    $classes = [
        DiagnoseQuery::class,
        DiagnoseHandler::class,
        DoctorQuery::class,
        DoctorHandler::class,
        ListQuery::class,
        ListHandler::class,
        ShowQuery::class,
        ShowHandler::class,
        QueryBusInterface::class,
        QueryHandlerInterface::class,
        QueryInterface::class,

        ExtensionVersionProviderInterface::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
