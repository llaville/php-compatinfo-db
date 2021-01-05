<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, yac extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Yac;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.3.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class YacExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            'YAC_SERIALIZER_MSGPACK',   // requires YAC_ENABLE_MSGPACK
            'YAC_SERIALIZER_IGBINARY',  // requires YAC_ENABLE_IGBINARY
            'YAC_SERIALIZER_JSON',      // requires YAC_ENABLE_JSON
        ];

        self::$optionalmethods = [
            'Yac::__get',
            'Yac::__set',
        ];

        parent::setUpBeforeClass();
    }
}
