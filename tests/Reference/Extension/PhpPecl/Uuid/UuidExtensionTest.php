<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Uuid;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use function array_push;

/**
 * Unit tests for PHP_CompatInfo_Db, uuid extension Reference
 *
 * @since Release 3.9.0 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class UuidExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        // ifdef UUID_TYPE_DCE_TIME_V6
        array_push(self::$optionalconstants, 'UUID_TYPE_TIME_V6');

        // ifdef UUID_TYPE_DCE_TIME_V7
        array_push(self::$optionalconstants, 'UUID_TYPE_TIME_V7');

        // ifdef UUID_TYPE_DCE_VENDOR
        array_push(self::$optionalconstants, 'UUID_TYPE_VENDOR');

        parent::setUpBeforeClass();
    }
}
