<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, mbstring extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Mbstring;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
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
