<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Memcache;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, memcache extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class MemcacheExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            'MEMCACHE_SERIALIZED'
        );

        if (PATH_SEPARATOR == ';') {
            // Win32 only
            self::$optionalfunctions = array(
                'memcache_append',
                'memcache_cas',
                'memcache_prepend',
                'memcache_set_failure_callback',
            );
            self::$ignoredfunctions = array(
                'memcache_setoptimeout',
            );
            array_push(
                self::$optionalconstants,
                'MEMCACHE_USER1',
                'MEMCACHE_USER2',
                'MEMCACHE_USER3',
                'MEMCACHE_USER4'
            );
        }

        parent::setUpBeforeClass();
    }
}
