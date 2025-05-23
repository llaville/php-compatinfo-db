<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Pgsql;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, pgsql extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class PgsqlExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            'PGSQL_CONNECTION_SSL_STARTUP',
            // depends on LIBPQ_HAS_PIPELINING when adding PHP 8.3 support
            'PGSQL_PIPELINE_SYNC',
            'PGSQL_PIPELINE_ON',
            'PGSQL_PIPELINE_OFF',
            'PGSQL_PIPELINE_ABORTED',
            // depends on TRACE support (PQTRACE_SUPPRESS_TIMESTAMPS, PQTRACE_REGRESS_MODE)
            'PGSQL_TRACE_SUPPRESS_TIMESTAMPS',
            'PGSQL_TRACE_REGRESS_MODE',
            // requires HAVE_PQERRORS_SQLSTATE
            // (since PHP 8.4, @see https://github.com/php/php-src/commit/65ff5117ab191c1a17754e40d4ba2fef78829ad2)
            'PGSQL_ERRORS_SQLSTATE',
            // requires HAVE_PG_SET_CHUNKED_ROWS_SIZE
            'PGSQL_TUPLES_CHUNK',
        ];

        self::$optionalfunctions = [
            // depends on LIBPQ_HAS_PIPELINING when adding PHP 8.3 support
            'pg_enter_pipeline_mode',
            'pg_exit_pipeline_mode',
            'pg_pipeline_status',
            'pg_pipeline_sync',
            // requires HAVE_PG_SET_CHUNKED_ROWS_SIZE
            'pg_set_chunked_rows_size',
            // requires HAVE_PG_RESULT_MEMORY_SIZE
            'pg_result_memory_size',
        ];

        parent::setUpBeforeClass();
    }
}
