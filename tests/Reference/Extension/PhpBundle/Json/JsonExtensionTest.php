<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Json;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, json extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class JsonExtensionTest extends GenericTestCase
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
