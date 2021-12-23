<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Xcache;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, xcache extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class XcacheExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalcfgs = array(
            'xcache.admin.user',
            'xcache.admin.pass',
            'xcache.optimizer',
            // removed in 1.2.0
            'xcache.coveragedumper',
            // Windows only
            'xcache.coredump_type',
        );
        self::$optionalfunctions = array(
            // Requires specific build options
            // so not available everywhere
            'xcache_dasm_file',
            'xcache_dasm_string',
        );
        self::$ignoredconstants = array(
            'XC_OPSPEC_FETCHTYPE',
        );

        parent::setUpBeforeClass();
    }
}
