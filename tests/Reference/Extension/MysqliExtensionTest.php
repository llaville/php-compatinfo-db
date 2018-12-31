<?php

declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo, mysqli extension Reference
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

namespace Bartlett\Tests\CompatInfoDb\Reference\Extension;

use Bartlett\Tests\CompatInfoDb\Reference\GenericTest;

/**
 * Tests for PHP_CompatInfo, retrieving components informations
 * about mysqli extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class MysqliExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
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
            // Requires MYSQL_VERSION_ID >= 50007 or MYSQLI_USE_MYSQLND
            'MYSQLI_STMT_ATTR_PREFETCH_ROWS',
            // Requires MYSQL_VERSION_ID >= 50003 or MYSQLI_USE_MYSQLND
            'MYSQLI_STMT_ATTR_CURSOR_TYPE',
            'MYSQLI_CURSOR_TYPE_NO_CURSOR',
            'MYSQLI_CURSOR_TYPE_READ_ONLY',
            'MYSQLI_CURSOR_TYPE_FOR_UPDATE',
            'MYSQLI_CURSOR_TYPE_SCROLLABLE',
            // Requires MYSQL_VERSION_ID > 50002 or MYSQLI_USE_MYSQLND
            'MYSQLI_TYPE_NEWDECIMAL',
            'MYSQLI_TYPE_BIT',
            // Requires MYSQL_VERSION_ID >= 50001 or MYSQLI_USE_MYSQLND
            'MYSQLI_NO_DEFAULT_VALUE_FLAG',
        );
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
