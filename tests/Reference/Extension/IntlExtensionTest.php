<?php
/**
 * Unit tests for PHP_CompatInfo, intl extension Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version    GIT: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

namespace Bartlett\Tests\CompatInfoDb\Reference\Extension;

use Bartlett\Tests\CompatInfoDb\Reference\GenericTest;

/**
 * Tests for PHP_CompatInfo, retrieving components informations
 * about intl extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class IntlExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        if (PATH_SEPARATOR == ';') {
            // Win*
            self::$optionalclasses  = array('IntlException');

            if (version_compare(PHP_VERSION, '5.4.0', 'lt')) {
                self::$optionalconstants = array(
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
                );
            }
        }

        self::$optionalcfgs = array(
            'intl.use_exceptions'
        );

        parent::setUpBeforeClass();
    }
}
