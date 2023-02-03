<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Ldap;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, ldap extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class LdapExtensionTest extends GenericTestCase
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
