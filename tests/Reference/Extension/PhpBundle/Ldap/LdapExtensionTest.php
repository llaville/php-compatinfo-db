<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, ldap extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Ldap;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class LdapExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = array(
            // Requires LDAP SASL
            'ldap_sasl_bind',
            // Requires OpenLdap
            'ldap_set_rebind_proc',
        );

        self::$optionalconstants = [
            // Requires LDAP SASL
            'LDAP_OPT_X_SASL_MECH',
            'LDAP_OPT_X_SASL_REALM',
            'LDAP_OPT_X_SASL_AUTHCID',
            'LDAP_OPT_X_SASL_AUTHZID',
            // RFC 3829
            'LDAP_CONTROL_AUTHZID_REQUEST',
            'LDAP_CONTROL_AUTHZID_RESPONSE',
        ];

        parent::setUpBeforeClass();
    }
}
