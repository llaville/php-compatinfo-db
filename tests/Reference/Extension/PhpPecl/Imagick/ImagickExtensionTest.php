<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, imagick extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Imagick;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class ImagickExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // not yet included in a release
        // @see https://github.com/Imagick/imagick/commit/eb587cd83c0952fb67549afd634ab6cdc48a4a05
        self::$ignoredcfgs = [
            'imagick.set_single_thread',
            'imagick.shutdown_sleep_count',
        ];

        parent::setUpBeforeClass();
    }
}
