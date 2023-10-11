<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Posix;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use Exception;

/**
 * Unit tests for PHP_CompatInfo_Db, posix extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class PosixExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = [
            // Requires HAVE_EACCESS
            'posix_eaccess',
        ];

        parent::setUpBeforeClass();
    }
}
