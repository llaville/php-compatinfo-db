<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Sphinx;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, sphinx extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class SphinxExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        // Constants conditionally exists (according to libsphinx version)
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
