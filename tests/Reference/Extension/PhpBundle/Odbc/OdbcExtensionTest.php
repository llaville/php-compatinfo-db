<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Odbc;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, odbc extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class OdbcExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // This constants require ODBC >= 3.0.0
        self::$optionalconstants = array(
            // Standard data types
            'SQL_TYPE_DATE',
            'SQL_TYPE_TIME',
            'SQL_TYPE_TIMESTAMP',
            // SQLSpecialColumns values
            'SQL_BEST_ROWID',
            'SQL_ROWVER',
            'SQL_SCOPE_CURROW',
            'SQL_SCOPE_SESSION',
            'SQL_SCOPE_TRANSACTION',
            'SQL_NO_NULLS',
            'SQL_NULLABLE',
            // SQLStatistics values
            'SQL_INDEX_UNIQUE',
            'SQL_INDEX_ALL',
            'SQL_ENSURE',
            'SQL_QUICK',
        );

        if (PATH_SEPARATOR == ';') {
            // Windows only
            array_push(self::$optionalconstants, 'SQL_WCHAR', 'SQL_WVARCHAR', 'SQL_WLONGVARCHAR');
        }

        parent::setUpBeforeClass();
    }
}
