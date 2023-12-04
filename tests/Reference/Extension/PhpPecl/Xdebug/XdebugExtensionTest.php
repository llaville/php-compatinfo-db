<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Xdebug;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, xdebug extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class XdebugExtensionTest extends GenericTestCase
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

        self::$ignoredconstants = [
            'XDEBUG_PATH_INCLUDE',
            'XDEBUG_PATH_EXCLUDE',
            'XDEBUG_NAMESPACE_INCLUDE',
            'XDEBUG_NAMESPACE_EXCLUDE',
        ];

        parent::setUpBeforeClass();
    }
}
