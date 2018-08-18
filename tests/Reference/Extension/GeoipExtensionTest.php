<?php
/**
 * Unit tests for PHP_CompatInfo, geoip extension Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version    GIT: $Id$
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0RC1 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

namespace Bartlett\Tests\CompatInfoDb\Reference\Extension;

use Bartlett\Tests\CompatInfoDb\Reference\GenericTest;

/**
 * Tests for PHP_CompatInfo, retrieving components informations
 * about geoip extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @version    Release: @package_version@
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class GeoipExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
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
