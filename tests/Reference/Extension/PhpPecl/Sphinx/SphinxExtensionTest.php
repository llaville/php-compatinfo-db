<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, sphinx extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Sphinx;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class SphinxExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // Constants conditionnaly exists (according to libsphinx version)
        self::$optionalconstants = array(
            'SPH_RANK_EXPR',
            'SPH_RANK_FIELDMASK',
            'SPH_RANK_MATCHANY',
            'SPH_RANK_PROXIMITY',
            'SPH_RANK_SPH04',
            'SPH_RANK_TOTAL',
            // only defined when build with --enable-redis-igbinary option
            'SERIALIZER_IGBINARY'
        );

        parent::setUpBeforeClass();
    }
}
