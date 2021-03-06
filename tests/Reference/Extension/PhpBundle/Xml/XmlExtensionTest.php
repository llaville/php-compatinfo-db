<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, xml extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Xml;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
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
