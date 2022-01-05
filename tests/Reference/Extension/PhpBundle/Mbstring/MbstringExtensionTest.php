<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Mbstring;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, mbstring extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class MbstringExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // WARNING: strange to found it on PHP 7.1.x versions, while it supposed to appears since PHP 7.2.0alpha1
        self::$ignoredfunctions = array(
            'mb_chr',
            'mb_ord',
            'mb_scrub',
        );

        // WARNING: strange to find it on PHP 7.3.x versions, while it supposed to appears since PHP 7.4.0alpha1
        self::$ignoredfunctions = [
            'mb_str_split',
        ];

        parent::setUpBeforeClass();
    }
}
