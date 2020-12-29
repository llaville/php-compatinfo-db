<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, opcache extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Opcache;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class OpcacheExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalcfgs = array(
            // if HAVE_OPCACHE_FILE_CACHE
            'opcache.file_cache',
            'opcache.file_cache_only',
            'opcache.file_cache_consistency_checks',
            // if ENABLE_FILE_CACHE_FALLBACK
            'opcache.file_cache_fallback',
        );

        if (PATH_SEPARATOR == ':') {
            // Windows only
            array_push(self::$optionalcfgs, 'opcache.mmap_base');
        }

        parent::setUpBeforeClass();
    }
}
