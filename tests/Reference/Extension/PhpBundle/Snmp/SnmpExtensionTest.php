<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Snmp;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, snmp extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class SnmpExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (PATH_SEPARATOR == ';') {
            // Win32 only
            self::$optionalconstants = array(
                'SNMP_OID_OUTPUT_FULL',
                'SNMP_OID_OUTPUT_NUMERIC',
            );
            self::$optionalfunctions = array(
                'snmp_set_enum_print',
                'snmp_set_oid_output_format',
                'snmp_set_oid_numeric_print',
            );
        }

        parent::setUpBeforeClass();
    }
}
