<?php declare(strict_types=1);

/**
 * Query Bus contract.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query;

/**
 * @since 3.0.0
 */
interface QueryBusInterface
{
    /**
     * @param QueryInterface $query
     * @return mixed
     */
    public function query(QueryInterface $query);
}
