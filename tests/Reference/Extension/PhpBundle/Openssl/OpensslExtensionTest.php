<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Openssl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, openssl extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class OpensslExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
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
            // before PHP 8.4 (@see https://github.com/php/php-src/commit/98e2948ca7abaf38358ec4f938470aca2568f263)
            'X509_PURPOSE_ANY',
        );

        parent::setUpBeforeClass();
    }
}
