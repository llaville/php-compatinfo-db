<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Hash;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, mhash extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class MhashExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            'MHASH_MD2',
            'MHASH_RIPEMD128',
            'MHASH_RIPEMD256',
            'MHASH_RIPEMD320',
            'MHASH_SHA224',
            'MHASH_SHA384',
            'MHASH_SHA512',
            'MHASH_SNEFRU128',
            'MHASH_SNEFRU256',
            'MHASH_WHIRLPOOL',
        );

        parent::setUpBeforeClass();
    }
}
