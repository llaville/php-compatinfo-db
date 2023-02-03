<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Geoip;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, geoip extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class GeoipExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = array(
            // requires GeoIP C library 1.4.1 or higher (LIBGEOIP_VERSION >= 1004001)
            'geoip_region_name_by_code',
            'geoip_time_zone_by_country_and_region',
            // requires GeoIP C library 1.4.5 or higher (LIBGEOIP_VERSION >= 1004005)
            'geoip_country_code_by_name_v6',
            'geoip_country_code3_by_name_v6',
            'geoip_country_name_by_name_v6',
        );
        self::$optionalconstants = array(
            // requires GeoIP C library 1.4.8 or higher (LIBGEOIP_VERSION >= 1004008)
            'GEOIP_NETSPEED_EDITION_REV1',
        );

        parent::setUpBeforeClass();
    }
}
