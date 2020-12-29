<?php declare(strict_types=1);

/**
 * Value Object of console diagnose command.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Diagnose;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

use Doctrine\DBAL\Connection;

/**
 * @since Release 2.0.0RC1
 */
final class DiagnoseQuery implements QueryInterface
{
    /** @var Connection */
    private $connection;

    /**
     * DiagnoseQuery constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns Doctrine database connection.
     *
     * @return Connection
     */
    public function getDatabaseConnection(): Connection
    {
        return $this->connection;
    }
}
