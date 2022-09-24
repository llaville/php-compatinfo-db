<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Fileinfo;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

use Exception;

/**
 * Unit tests for PHP_CompatInfo_Db, fileinfo extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class FileinfoExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // @see https://github.com/php/php-src/commit/6b14989001145b91f01c152e419bfcd33bf7ac4b
            'FILEINFO_APPLE',
        ];

        parent::setUpBeforeClass();
    }
}
