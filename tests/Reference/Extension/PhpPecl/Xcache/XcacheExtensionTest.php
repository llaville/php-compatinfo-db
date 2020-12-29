<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, xcache extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Xcache;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
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
