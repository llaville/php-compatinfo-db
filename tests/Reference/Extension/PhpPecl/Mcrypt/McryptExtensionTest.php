<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Mcrypt;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, mcrypt extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class McryptExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
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
