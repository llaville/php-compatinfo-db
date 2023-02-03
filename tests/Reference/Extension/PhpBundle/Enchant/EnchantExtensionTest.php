<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Enchant;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, enchant extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class EnchantExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$ignoredfunctions = [
            // since PHP 8.0.0alpha1; @see https://github.com/php/php-src/commit/66d42e98844de694c6f77c299a3dc045ac5a3261
            'enchant_broker_get_dict_path',
            'enchant_broker_set_dict_path',
            'enchant_dict_add_to_personal',
            'enchant_dict_add',
            'enchant_dict_is_in_session',
            'enchant_dict_is_added',
        ];

        self::$ignoredconstants = [
            'LIBENCHANT_VERSION',
            'ENCHANT_ISPELL',
            'ENCHANT_MYSPELL',
        ];
        parent::setUpBeforeClass();
    }
}
