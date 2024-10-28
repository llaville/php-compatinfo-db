<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Mysqli;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use function version_compare;
use const PHP_VERSION;

/**
 * Unit tests for PHP_CompatInfo_Db, mysqli extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class MysqliExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            // Requires MYSQLI_USE_MYSQLND
            'MYSQLI_OPT_NET_CMD_BUFFER_SIZE',
            'MYSQLI_OPT_NET_READ_BUFFER_SIZE',
            'MYSQLI_OPT_INT_AND_FLOAT_NATIVE',
            'MYSQLI_ASYNC',
            'MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT',
            // Requires CLIENT_SSL_VERIFY_SERVER_CERT
            'MYSQLI_CLIENT_SSL_VERIFY_SERVER_CERT',
            // Requires (MYSQL_VERSION_ID > 51122 and MYSQL_VERSION_ID < 60000) or MYSQL_VERSION_ID > 60003 or MYSQLI_USE_MYSQLND
            'MYSQLI_ON_UPDATE_NOW_FLAG',
            // Requires REFRESH_BACKUP_LOG
            'MYSQLI_REFRESH_BACKUP_LOG',
            // Requires SERVER_QUERY_WAS_SLOW
            'MYSQLI_SERVER_QUERY_WAS_SLOW',
            // Requires SERVER_PS_OUT_PARAMS
            'MYSQLI_SERVER_PS_OUT_PARAMS',
            // Requires MYSQL_VERSION_ID >= 50611 or MYSQLI_USE_MYSQLND
            'MYSQLI_OPT_CAN_HANDLE_EXPIRED_PASSWORDS',
            'MYSQLI_CLIENT_CAN_HANDLE_EXPIRED_PASSWORDS',
            // Requires MYSQL_VERSION_ID >= 50605 or MYSQLI_USE_MYSQLND
            'MYSQLI_SERVER_PUBLIC_KEY',
            // Requires MYSQL_VERSION_ID >= 50110 or MYSQLI_USE_MYSQLND
            'MYSQLI_OPT_SSL_VERIFY_SERVER_CERT',
            // Requires MYSQL_VERSION_ID >= 50003 or MYSQLI_USE_MYSQLND
            'MYSQLI_STMT_ATTR_CURSOR_TYPE',
            'MYSQLI_CURSOR_TYPE_NO_CURSOR',
            'MYSQLI_CURSOR_TYPE_READ_ONLY',
            // Requires MYSQL_VERSION_ID > 50002 or MYSQLI_USE_MYSQLND
            'MYSQLI_TYPE_NEWDECIMAL',
            'MYSQLI_TYPE_BIT',
            // Requires MYSQL_VERSION_ID >= 50001 or MYSQLI_USE_MYSQLND
            'MYSQLI_NO_DEFAULT_VALUE_FLAG',
        );

        if (version_compare(PHP_VERSION, '8.4.0beta3', 'le')) {
            // Requires MYSQL_VERSION_ID >= 50007 or MYSQLI_USE_MYSQLND
            self::$optionalconstants[] = 'MYSQLI_STMT_ATTR_PREFETCH_ROWS';
            // Requires MYSQL_VERSION_ID >= 50003 or MYSQLI_USE_MYSQLND
            self::$optionalconstants[] = 'MYSQLI_CURSOR_TYPE_FOR_UPDATE';
            self::$optionalconstants[] = 'MYSQLI_CURSOR_TYPE_SCROLLABLE';
        }

        self::$optionalfunctions = array(
            // Requires HAVE_EMBEDDED_MYSQLI
            'mysqli_embedded_server_end',
            'mysqli_embedded_server_start',
            // Requires MYSQLI_USE_MYSQLND
            'mysqli_fetch_all',
            'mysqli_get_cache_stats',
            'mysqli_get_connection_stats',
            'mysqli_get_client_stats',
            'mysqli_set_local_infile_default',
            'mysqli_set_local_infile_handler',
            'mysqli_poll',
            'mysqli_reap_async_query',
            'mysqli_stmt_get_result',
            'mysqli_stmt_more_results',
            'mysqli_stmt_next_result',
        );

        parent::setUpBeforeClass();
    }
}
