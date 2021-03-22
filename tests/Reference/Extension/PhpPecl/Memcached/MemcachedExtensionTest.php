<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, memcached extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Memcached;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class MemcachedExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalcfgs = [
            // requires HAVE_MEMCACHED_SASL
            'memcached.use_sasl',
            // requires HAVE_MEMCACHED_SESSION
            'memcached.sess_binary',
            'memcached.sess_remove_failed',
        ];

        self::$ignoredconsts = [
            // included since release 3.1.5 into master branch
            // @see https://github.com/php-memcached-dev/php-memcached/commit/f97b2e3ad95bbd83c862abba08c34ac3f4acc497
            'Memcached::RES_CONNECTION_BIND_FAILURE',
            'Memcached::RES_READ_FAILURE',
            'Memcached::RES_DATA_DOES_NOT_EXIST',
            'Memcached::RES_VALUE',
            'Memcached::RES_FAIL_UNIX_SOCKET',
            'Memcached::RES_NO_KEY_PROVIDED',
            'Memcached::RES_INVALID_ARGUMENTS',
            'Memcached::RES_PARSE_ERROR',
            'Memcached::RES_PARSE_USER_ERROR',
            'Memcached::RES_DEPRECATED',
            'Memcached::RES_IN_PROGRESS',
            'Memcached::RES_MAXIMUM_RETURN',
        ];

        parent::setUpBeforeClass();
    }
}
