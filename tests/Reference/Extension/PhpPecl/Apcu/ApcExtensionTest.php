<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, apc extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Apcu;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class ApcExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalcfgs = array(
            'apc.mmap_file_mask',  // when APC_MMAP
            'apc.optimization',
        );

        if (PATH_SEPARATOR == ';') {
            // Win*
            array_push(
                self::$optionalcfgs,
                'apc.shm_strings_buffer'
            );
        }

        if (extension_loaded('apcu')) {
            // and PHP 5.5+
            array_push(
                self::$optionalcfgs,
                'apc.cache_by_default',
                'apc.canonicalize',
                'apc.file_md5',
                'apc.file_update_protection',
                'apc.filters',
                'apc.include_once_override',
                'apc.lazy_classes',
                'apc.lazy_functions',
                'apc.max_file_size',
                'apc.num_files_hint',
                'apc.report_autofilter',
                'apc.shm_strings_buffer',
                'apc.stat',
                'apc.stat_ctime',
                'apc.user_entries_hint',
                'apc.user_ttl',
                'apc.write_lock'
            );
        }

        // Constants and Classes not available in CLI mode
        self::$optionalconstants = array(
            'APC_LIST_ACTIVE',
            'APC_LIST_DELETED',
            'APC_ITER_TYPE',
            'APC_ITER_KEY',
            'APC_ITER_FILENAME',
            'APC_ITER_DEVICE',
            'APC_ITER_INODE',
            'APC_ITER_VALUE',
            'APC_ITER_MD5',
            'APC_ITER_NUM_HITS',
            'APC_ITER_MTIME',
            'APC_ITER_CTIME',
            'APC_ITER_DTIME',
            'APC_ITER_ATIME',
            'APC_ITER_REFCOUNT',
            'APC_ITER_MEM_SIZE',
            'APC_ITER_TTL',
            'APC_ITER_NONE',
            'APC_ITER_ALL',
            'APC_BIN_VERIFY_MD5',
            'APC_BIN_VERIFY_CRC32',
        );
        self::$optionalclasses = array(
            'APCIterator',
        );
        if (extension_loaded('apcu')) {
            // APCu is a drop in replacement for APC
            // present as "apc" but only provides user data cache functions
            self::$optionalfunctions = array(
                'apc_define_constants',
                'apc_load_constants',
                'apc_compile_file',
                'apc_delete_file',
            );
        }

        parent::setUpBeforeClass();
    }
}
