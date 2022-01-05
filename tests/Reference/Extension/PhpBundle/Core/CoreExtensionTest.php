<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Core;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, core extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class CoreExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            // Not real constant
            '__CLASS__',
            '__FILE__',
            '__FUNCTION__',
            '__LINE__',
            '__COMPILER_HALT_OFFSET__',
            '__DIR__',
            '__METHOD__',
            '__NAMESPACE__',
            '__TRAIT__',
        );
        self::$ignoredconstants = array(
            // add by swig framework as core constant
            'swig_runtime_data_type_pointer',
        );
        self::$ignoredfunctions = array(
            // Provided by PHP/CodeCoverage/Util.php when not available in PHP
            // So no reliable check for this one
            'trait_exists',
        );
        self::$optionalcfgs = array(
            // Requires --enable-zend-multibyte
            'zend.detect_unicode',
            'zend.multibyte'
        );
        if (PATH_SEPARATOR == ':') {
            self::$optionalcfgs = array_merge(
                self::$optionalcfgs,
                array(
                    'windows.show_crt_warning',
                )
            );
            self::$optionalconstants = array_merge(
                self::$optionalconstants,
                array(
                    // Win32 Only
                    'PHP_WINDOWS_VERSION_MAJOR',
                    'PHP_WINDOWS_VERSION_MINOR',
                    'PHP_WINDOWS_VERSION_BUILD',
                    'PHP_WINDOWS_VERSION_PLATFORM',
                    'PHP_WINDOWS_VERSION_SP_MAJOR',
                    'PHP_WINDOWS_VERSION_SP_MINOR',
                    'PHP_WINDOWS_VERSION_SUITEMASK',
                    'PHP_WINDOWS_VERSION_PRODUCTTYPE',
                    'PHP_WINDOWS_NT_DOMAIN_CONTROLLER',
                    'PHP_WINDOWS_NT_SERVER',
                    'PHP_WINDOWS_NT_WORKSTATION',
                )
            );
        } else {
            self::$optionalconstants = array_merge(
                self::$optionalconstants,
                array(
                    // Non Windows only
                    'PHP_MANDIR',
                )
            );
        }
        if (php_sapi_name() != 'cli') {
            array_push(self::$optionalconstants, 'STDIN', 'STDOUT', 'STDERR');
        }

        self::$optionalfunctions = array(
            'empty',
            'isset',
            'list',
            // Requires ZTS
            'zend_thread_id',
        );

        // special classes
        self::$optionalclasses = array(
            'parent',
            'static',
            'self',
        );

        self::$optionalinterfaces = [
            'Stringable',
        ];

        self::$optionalmethods = [
            'Closure::__invoke',
            'Generator::__wakeup',
        ];

        parent::setUpBeforeClass();
    }
}
