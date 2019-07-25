<?php

declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo, mcrypt extension Reference
 *
 * PHP version 7
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
 * about mcrypt extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 *
 * @requires PHP < 7.2
 */
class McryptExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$optionalconstants = array(
            // Non-free
            'MCRYPT_IDEA',
            // only in libmcrypt = 2.2.x
            'MCRYPT_DES_COMPAT',
            'MCRYPT_RC4',
            'MCRYPT_RC6_128',
            'MCRYPT_RC6_192',
            'MCRYPT_RC6_256',
            'MCRYPT_SERPENT_128',
            'MCRYPT_SERPENT_192',
            'MCRYPT_SERPENT_256',
            'MCRYPT_TEAN',
            'MCRYPT_TWOFISH128',
            'MCRYPT_TWOFISH192',
            'MCRYPT_TWOFISH256',
            // only in libmcrypt > 2.4.x
            'MCRYPT_ARCFOUR_IV',
            'MCRYPT_ARCFOUR',
            'MCRYPT_ENIGNA',
            'MCRYPT_LOKI97',
            'MCRYPT_MARS',
            'MCRYPT_PANAMA',
            'MCRYPT_RIJNDAEL_128',
            'MCRYPT_RIJNDAEL_192',
            'MCRYPT_RIJNDAEL_256',
            'MCRYPT_RC6',
            'MCRYPT_SAFERPLUS',
            'MCRYPT_SERPENT',
            'MCRYPT_SKIPJACK',
            'MCRYPT_TRIPLEDES',
            'MCRYPT_TWOFISH',
            'MCRYPT_WAKE',
            'MCRYPT_XTEA',
        );
        self::$ignoredconstants = array(
            'MCRYPT_BLOWFISH_COMPAT',
        );

        parent::setUpBeforeClass();
    }
}
