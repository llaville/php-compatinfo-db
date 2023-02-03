<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Yac;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, yac extension Reference
 *
 * @since Release 3.3.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class YacExtensionTest extends GenericTestCase
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
