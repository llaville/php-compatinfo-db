<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Xml;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, xml extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class XmlExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalclasses = [
            'XMLParser',    // since PHP 8.0.1 (see https://github.com/php/php-src/commit/a55402d07c12bb2eda4a41e4fc4a20d49ef17142)
        ];
        self::$ignoredclasses = [
            'XmlParser',    // ReflectionClass('XmlParser') returns a result with PHP 8.0.1
        ];

        parent::setUpBeforeClass();
    }
}
