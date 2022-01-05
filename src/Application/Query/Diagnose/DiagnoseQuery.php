<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Diagnose;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

use Doctrine\DBAL\Connection;

/**
 * Value Object of console diagnose command.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class DiagnoseQuery implements QueryInterface
{
    private Connection $connection;

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
