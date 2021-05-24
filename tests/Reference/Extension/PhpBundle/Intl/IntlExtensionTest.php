<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, intl extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Intl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class IntlExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (PATH_SEPARATOR == ';') {
            // Win*
            self::$optionalclasses  = ['IntlException'];

            if (version_compare(PHP_VERSION, '5.4.0', 'lt')) {
                self::$optionalconstants = [
                    'U_IDNA_PROHIBITED_ERROR',
                    'U_IDNA_ERROR_START',
                    'U_IDNA_UNASSIGNED_ERROR',
                    'U_IDNA_CHECK_BIDI_ERROR',
                    'U_IDNA_STD3_ASCII_RULES_ERROR',
                    'U_IDNA_ACE_PREFIX_ERROR',
                    'U_IDNA_VERIFICATION_ERROR',
                    'U_IDNA_LABEL_TOO_LONG_ERROR',
                    'U_IDNA_ZERO_LENGTH_LABEL_ERROR',
                    'U_IDNA_ERROR_LIMIT',
                ];
            }
        } else {
            self::$optionalfunctions = [
                'intltz_get_windows_id',
                'intltz_get_id_for_windows_id',
            ];
        }

        self::$optionalcfgs = [
            'intl.use_exceptions'
        ];

        parent::setUpBeforeClass();
    }
}
