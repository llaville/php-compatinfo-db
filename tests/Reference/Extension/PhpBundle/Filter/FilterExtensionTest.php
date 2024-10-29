<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Filter;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, filter extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class FilterExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = array(
            'FILTER_SANITIZE_ALL',
            'FILTER_VALIDATE_ALL',

            // ignores all old API constants before 0.11.0
            'FILTER_FLAG_ARRAY',
            'FILTER_FLAG_SCALAR',
        );

        // ignores all old API functions before 0.11.0
        self::$optionalfunctions = array(
            'input_get',
            'input_filters_list',
            'input_has_variable',
            'filter_data',
            'input_name_to_filter',
            'input_get_args',
        );

        self::$ignoredconstants = [
            // exists on PHP 7.x even if https://github.com/php/php-src/commit/f13d0a72d5cf92785c91ffc33c27df3df3f8e96e
            'FILTER_VALIDATE_BOOL',
        ];

        parent::setUpBeforeClass();
    }
}
