<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, enchant extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Enchant;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class EnchantExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$ignoredfunctions = [
            'enchant_broker_get_dict_path',
            'enchant_broker_set_dict_path',
            'enchant_dict_add_to_personal',
            'enchant_dict_add',
            'enchant_dict_is_in_session',
        ];

        self::$ignoredconstants = [
            'LIBENCHANT_VERSION',
            'ENCHANT_ISPELL',
            'ENCHANT_MYSPELL',
        ];
        parent::setUpBeforeClass();
    }
}
