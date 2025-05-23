<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Gmp;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, gmp extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class GmpExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            'GMP_MPIR_VERSION',
        ];

        self::$ignoredclasses = [
            'GMP',  // empty class in PHP 5.6
        ];

        parent::setUpBeforeClass();
    }
}
