<?php
/**
 * Unit tests for PHP_CompatInfo, odbc extension Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0RC1 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

namespace Bartlett\Tests\CompatInfoDb\Reference\Extension;

use Bartlett\Tests\CompatInfoDb\Reference\GenericTest;

/**
 * Tests for PHP_CompatInfo, retrieving components informations
 * about odbc extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class OdbcExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
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
