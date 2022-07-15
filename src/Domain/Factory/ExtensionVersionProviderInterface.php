<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Factory;

/**
 * Declares all PHP latest supported versions.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
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
    public const LATEST_PHP_7_3 = '7.3.33';
    public const LATEST_PHP_7_4 = '7.4.30';
    public const LATEST_PHP_8_0 = '8.0.21';
    public const LATEST_PHP_8_1 = '8.1.8';
}
