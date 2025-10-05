<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Opcache;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, opcache extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class OpcacheExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalcfgs = array(
            // if HAVE_OPCACHE_FILE_CACHE
            'opcache.file_cache',
            'opcache.file_cache_only',
            'opcache.file_cache_consistency_checks',
            'opcache.file_cache_read_only',
            // if ENABLE_FILE_CACHE_FALLBACK
            'opcache.file_cache_fallback',
            // strange result behaviour on CI
            'opcache.inherited_hack',
        );

        if (PATH_SEPARATOR == ':') {
            // Windows only
            array_push(self::$optionalcfgs, 'opcache.mmap_base');
        }

        parent::setUpBeforeClass();
    }
}
