<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Xmldiff;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, xmldiff extension Reference
 *
 * @since Release 4.0.0-beta1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class XmldiffExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalclasses = [
            'XMLDiff\\Base',    // abstract class
        ];
        self::$optionalmethods = [
            'XMLDiff\\Base::__construct',
            'XMLDiff\\Base::diff',
            'XMLDiff\\Base::merge',
        ];

        parent::setUpBeforeClass();
    }
}
