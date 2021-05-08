<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, http extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Http;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

use function array_keys;
use function array_push;
use function is_null;
use function phpversion;
use function version_compare;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class HttpExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // when age >=1
            'http\\Client\\Curl\\Versions\\ARES',
            // when age >=2
            'http\\Client\\Curl\\Versions\\IDN',
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
