<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, xdebug extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Xdebug;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class XdebugExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = [];
        $extname = 'xdebug';
        if (extension_loaded($extname)) {
            if (version_compare(phpversion($extname), '2.0.0beta1', 'ge')) {
                // removed functions in 2.0.0beta1
                array_push(
                    self::$optionalfunctions,
                    'xdebug_get_function_trace',
                    'xdebug_dump_function_trace'
                );
            }

            if (version_compare(phpversion($extname), '2.0.0RC1', 'ge')) {
                // removed functions in 2.0.0RC1
                array_push(
                    self::$optionalfunctions,
                    'xdebug_set_error_handler'
                );
            }
        }

        self::$optionalconstants = [
            'XDEBUG_CC_DEAD_CODE',
            'XDEBUG_CC_UNUSED',
            'XDEBUG_CC_BRANCH_CHECK',
            'XDEBUG_FILTER_CODE_COVERAGE',
            'XDEBUG_FILTER_NONE',
            'XDEBUG_FILTER_TRACING',
            'XDEBUG_FILTER_STACK',
            'XDEBUG_NAMESPACE_BLACKLIST',
            'XDEBUG_NAMESPACE_WHITELIST',
            'XDEBUG_PATH_BLACKLIST',
            'XDEBUG_PATH_WHITELIST',
            'XDEBUG_PATH_INCLUDE',
            'XDEBUG_STACK_NO_DESC',
            'XDEBUG_TRACE_APPEND',
            'XDEBUG_TRACE_COMPUTERIZED',
            'XDEBUG_TRACE_HTML',
            'XDEBUG_TRACE_NAKED_FILENAME',
        ];

        parent::setUpBeforeClass();
    }
}
