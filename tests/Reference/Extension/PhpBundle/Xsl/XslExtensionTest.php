<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Xsl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, xsl extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class XslExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (version_compare(PHP_VERSION, '5.3.3', 'eq')) {
            // Security fix backported in PHP 5.3.3 / RHEL-6
            self::$ignoredcfgs = array(
                'xsl.security_prefs',
            );
            self::$ignoredconstants = array(
                'XSL_SECPREF_CREATE_DIRECTORY',
                'XSL_SECPREF_DEFAULT',
                'XSL_SECPREF_NONE',
                'XSL_SECPREF_READ_FILE',
                'XSL_SECPREF_READ_NETWORK',
                'XSL_SECPREF_WRITE_FILE',
                'XSL_SECPREF_WRITE_NETWORK',
            );
        }

        parent::setUpBeforeClass();
    }
}
