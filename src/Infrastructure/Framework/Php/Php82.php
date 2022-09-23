<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Php;

/**
 * Allows to test/compare PHP 8.2 in dev status with other status (alpha, beta, RC)
 *
 * @since 4.5.0
 * @author Laurent Laville
 * @see https://github.com/llaville/php-compatinfo-db/issues/124
 */
final class Php82
{
    private static function fallbackStrategy(string $version1, string $version2, ?string $operator): bool
    {
        return \version_compare($version1, $version2, $operator);
    }

    public static function versionCompare(string $version1, string $version2, ?string $operator): bool
    {
        if (\PHP_VERSION == '8.2.0-dev') {
            if ($version1 === '8.2.0-dev') {
                return self::fallbackStrategy('8.2.0RC99', $version2, $operator);
            }
        }
        return self::fallbackStrategy($version1, $version2, $operator);
    }
}

function version_compare(string $version1, string $version2, ?string $operator): bool
{
    return Php82::versionCompare($version1, $version2, $operator);
}
