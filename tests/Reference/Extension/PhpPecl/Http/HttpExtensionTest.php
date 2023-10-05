<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Http;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use function array_keys;
use function array_push;
use function is_null;
use function phpversion;
use function version_compare;

/**
 * Unit tests for PHP_CompatInfo_Db, http extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class HttpExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // before release 4.1.0, see commit https://github.com/m6w6/ext-http/commit/ccc68db494d5436acae7254f81ed111780e00d72
            // when age >=0
            'http\\Client\\Curl\\Versions\\CURL',
            'http\\Client\\Curl\\Versions\\SSL',
            'http\\Client\\Curl\\Versions\\LIBZ',
            // when age >=1
            'http\\Client\\Curl\\Versions\\ARES',
            // when age >=2
            'http\\Client\\Curl\\Versions\\IDN',
            // when OpenSSL built with TLS-SRP support (https://github.com/openssl/openssl/blob/openssl-3.0.9/INSTALL.md#no-srp)
            'http\\Client\\Curl\\TLSAUTH_SRP',
        ];

        self::$optionalclasses = [
            // requires libbrotli
            'http\\Encoding\\Stream\\Debrotli',
            'http\\Encoding\\Stream\\Enbrotli',
        ];

        // requires `iconv`; see https://mdref.m6w6.name/http#PHP.extensions:
        self::$optionalmethods = [
            'http\QueryString::xlate',
            // requires libbrotli
            'http\\Encoding\\Stream\\Debrotli::decode',
            'http\\Encoding\\Stream\\Enbrotli::encode',
        ];

        parent::setUpBeforeClass();

        if (!is_null(self::$obj)) {
            $currentVersion = phpversion(self::$obj->getName()) ? : '';

            // platform dependant
            if (version_compare($currentVersion, '2.0.0', 'lt')) {
                // v1, so all v2 releases are optionals
                $releases = array_keys(self::$obj->getReleases());
                foreach ($releases as $rel_version) {
                    if (version_compare($rel_version, '2.0.0', 'ge')) {
                        array_push(self::$optionalreleases, $rel_version);
                    }
                }
            } else {
                // v2, so all v1 releases must not be checked
                self::$optionalreleases = [
                    '0.7.0',
                    '1.0.0',
                    '1.3.0',
                    '1.5.0',
                    '1.7.0',
                ];
            }
        }
    }
}
