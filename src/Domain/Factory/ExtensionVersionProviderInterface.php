<?php declare(strict_types=1);

/**
 * Declares all PHP latest supported versions.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Domain\Factory;

/**
 * @since Release 3.0.0
 */
interface ExtensionVersionProviderInterface
{
    public const LATEST_PHP_5_2 = '5.2.17';
    public const LATEST_PHP_5_3 = '5.3.29';
    public const LATEST_PHP_5_4 = '5.4.45';
    public const LATEST_PHP_5_5 = '5.5.38';
    public const LATEST_PHP_5_6 = '5.6.40';
    public const LATEST_PHP_7_0 = '7.0.33';
    public const LATEST_PHP_7_1 = '7.1.33';
    public const LATEST_PHP_7_2 = '7.2.34';
    public const LATEST_PHP_7_3 = '7.3.27';
    public const LATEST_PHP_7_4 = '7.4.16';
    public const LATEST_PHP_8_0 = '8.0.3';
}
