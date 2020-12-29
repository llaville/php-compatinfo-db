<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, json extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Json;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class JsonExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // New features of JSONC alternative extension
        self::$ignoredconstants = array(
            'JSON_C_BUNDLED',
            'JSON_C_VERSION',
            'JSON_PARSER_NOTSTRICT',
        );
        self::$ignoredclasses = array(
            'JsonIncrementalParser',
            // @see https://github.com/symfony/polyfill-php73/blob/main/Resources/stubs/JsonException.php
            'JsonException'
        );

        parent::setUpBeforeClass();
    }
}
