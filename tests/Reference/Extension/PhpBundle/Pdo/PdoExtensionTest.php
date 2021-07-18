<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, pdo extension Reference
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Pdo;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class PdoExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalmethods = [
            'PDO::__sleep',
            'PDO::__wakeup',
            'PDOStatement::__sleep',
            'PDOStatement::__wakeup',
        ];

        self::$ignoredconsts = [
            // @see https://github.com/php/php-src/commit/ed2f6510da7c68b5690c60344cbb9fe73241592a
            'PDO::SQLITE_ATTR_OPEN_FLAGS',
            'PDO::SQLITE_OPEN_READONLY',
            'PDO::SQLITE_OPEN_READWRITE',
            'PDO::SQLITE_OPEN_CREATE',
            // @see https://github.com/php/php-src/commit/6f9ebe677bf31d0c64046f18e6b0ac75340cb93e
            'PDO::SQLITE_ATTR_READONLY_STATEMENT',
            // @see https://github.com/php/php-src/commit/b546ae986a6efe4daadd23e27f6ccaac5c857e5e
            'PDO::SQLITE_ATTR_EXTENDED_RESULT_CODES',
            // @see https://github.com/microsoft/msphpsql/blob/v5.9.0/source/pdo_sqlsrv/pdo_init.cpp#L296
            'PDO::SQLSRV_ATTR_ENCODING',
            'PDO::SQLSRV_ATTR_QUERY_TIMEOUT',
            'PDO::SQLSRV_ATTR_DIRECT_QUERY',
            'PDO::SQLSRV_ATTR_CURSOR_SCROLL_TYPE',
            'PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE',
            'PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE',
            'PDO::SQLSRV_ATTR_FETCHES_DATETIME_TYPE',
            'PDO::SQLSRV_ATTR_FORMAT_DECIMALS',
            'PDO::SQLSRV_ATTR_DECIMAL_PLACES',
            'PDO::SQLSRV_ATTR_DATA_CLASSIFICATION',
            'PDO::SQLSRV_PARAM_OUT_DEFAULT_SIZE',
            'PDO::SQLSRV_ENCODING_DEFAULT',
            'PDO::SQLSRV_ENCODING_SYSTEM',
            'PDO::SQLSRV_ENCODING_BINARY',
            'PDO::SQLSRV_ENCODING_UTF8',
            'PDO::SQLSRV_CURSOR_STATIC',
            'PDO::SQLSRV_CURSOR_DYNAMIC',
            'PDO::SQLSRV_CURSOR_KEYSET',
            'PDO::SQLSRV_CURSOR_BUFFERED',
            // @see https://github.com/microsoft/msphpsql/blob/v5.9.0/source/pdo_sqlsrv/pdo_init.cpp#L161-L165
            'PDO::SQLSRV_TXN_READ_UNCOMMITTED',
            'PDO::SQLSRV_TXN_READ_COMMITTED',
            'PDO::SQLSRV_TXN_REPEATABLE_READ',
            'PDO::SQLSRV_TXN_SERIALIZABLE',
            'PDO::SQLSRV_TXN_SNAPSHOT',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_dblib/pdo_dblib.c#L192-L198
            'PDO::DBLIB_ATTR_CONNECTION_TIMEOUT',
            'PDO::DBLIB_ATTR_QUERY_TIMEOUT',
            'PDO::DBLIB_ATTR_STRINGIFY_UNIQUEIDENTIFIER',
            'PDO::DBLIB_ATTR_VERSION',
            'PDO::DBLIB_ATTR_TDS_VERSION',
            'PDO::DBLIB_ATTR_SKIP_EMPTY_ROWSETS',
            'PDO::DBLIB_ATTR_DATETIME_CONVERT',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_odbc/pdo_odbc.c#L99-L103
            'PDO::ODBC_ATTR_USE_CURSOR_LIBRARY',
            'PDO::ODBC_ATTR_ASSUME_UTF8',
            'PDO::ODBC_SQL_USE_IF_NEEDED',
            'PDO::ODBC_SQL_USE_DRIVER',
            'PDO::ODBC_SQL_USE_ODBC',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_pgsql/pdo_pgsql.c#L61-L66
            'PDO::PGSQL_ATTR_DISABLE_PREPARES',
            'PDO::PGSQL_TRANSACTION_IDLE',
            'PDO::PGSQL_TRANSACTION_ACTIVE',
            'PDO::PGSQL_TRANSACTION_INTRANS',
            'PDO::PGSQL_TRANSACTION_INERROR',
            'PDO::PGSQL_TRANSACTION_UNKNOWN',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_oci/pdo_oci.c#L84-L88
            'PDO::OCI_ATTR_ACTION',
            'PDO::OCI_ATTR_CLIENT_INFO',
            'PDO::OCI_ATTR_CLIENT_IDENTIFIER',
            'PDO::OCI_ATTR_MODULE',
            'PDO::OCI_ATTR_CALL_TIMEOUT',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_mysql/pdo_mysql.c#L103-L126
            'PDO::MYSQL_ATTR_USE_BUFFERED_QUERY',
            'PDO::MYSQL_ATTR_LOCAL_INFILE',
            'PDO::MYSQL_ATTR_INIT_COMMAND',
            'PDO::MYSQL_ATTR_MAX_BUFFER_SIZE',
            'PDO::MYSQL_ATTR_READ_DEFAULT_FILE',
            'PDO::MYSQL_ATTR_READ_DEFAULT_GROUP',
            'PDO::MYSQL_ATTR_COMPRESS',
            'PDO::MYSQL_ATTR_DIRECT_QUERY',
            'PDO::MYSQL_ATTR_FOUND_ROWS',
            'PDO::MYSQL_ATTR_IGNORE_SPACE',
            'PDO::MYSQL_ATTR_SSL_KEY',
            'PDO::MYSQL_ATTR_SSL_CERT',
            'PDO::MYSQL_ATTR_SSL_CA',
            'PDO::MYSQL_ATTR_SSL_CAPATH',
            'PDO::MYSQL_ATTR_SSL_CIPHER',
            'PDO::MYSQL_ATTR_SERVER_PUBLIC_KEY',
            'PDO::MYSQL_ATTR_MULTI_STATEMENTS',
            'PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT',
            // @see https://github.com/php/php-src/blob/php-8.1.0alpha1/ext/pdo_mysql/pdo_mysql.c#L127-L129
            'PDO::MYSQL_ATTR_LOCAL_INFILE_DIRECTORY',
            // @see https://github.com/php/php-src/blob/php-8.0.3/ext/pdo_firebird/pdo_firebird.c#L57-L59
            'PDO::FB_ATTR_DATE_FORMAT',
            'PDO::FB_ATTR_TIME_FORMAT',
            'PDO::FB_ATTR_TIMESTAMP_FORMAT',
        ];

        parent::setUpBeforeClass();
    }
}
