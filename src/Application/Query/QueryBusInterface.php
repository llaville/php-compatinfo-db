<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query;

/**
 * Query Bus contract.
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}
