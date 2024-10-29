<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Reflection;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, reflection extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class ReflectionExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalmethods = [
            'Reflector::export',
            // introduced by commit https://github.com/php/php-src/commit/93f11d84294d7eaadb9d9fc3c0996ff30279011d
            // available since PHP 8.0.24RC1 and PHP 8.1.11RC1
            // CAUTION: other PHP 8.1 versions (8.1.0 until 8.1.10) does not support it !
            'ReflectionFunctionAbstract::getClosureCalledClass',
        ];

        parent::setUpBeforeClass();
    }
}
