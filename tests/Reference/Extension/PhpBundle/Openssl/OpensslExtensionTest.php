<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, openssl extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Openssl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class OpensslExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            // requires HAVE_OPENSSL_MD2_H
            'OPENSSL_ALGO_MD2',
            // requires OPENSSL_VERSION_NUMBER >= 0x0090806fL
            // and !OPENSSL_NO_TLSEXT
            'OPENSSL_TLSEXT_SERVER_NAME',
            // requires HAVE_EVP_PKEY_EC
            'OPENSSL_KEYTYPE_EC',
            // requires OPENSSL_VERSION_NUMBER < 0x10100000L or LIBRESSL_VERSION_NUMBER < 0x20700000L
            'OPENSSL_ALGO_DSS1',
        );

        parent::setUpBeforeClass();
    }
}
