<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Composer;

use OutOfBoundsException;
use function func_get_arg;
use function func_num_args;
use function sprintf;
use function substr;

/**
 * @author Laurent Laville
 * @since Release 3.19.0
 */
final class InstalledVersions
{
    public static function getPrettyVersion(string $packageName): ?string
    {
        if (func_num_args() >= 2) {
            $withRef = func_get_arg(1);
        } else {
            $withRef = true;
        }

        foreach (\Composer\InstalledVersions::getAllRawData() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }
            if (!empty($installed['versions'][$packageName]['aliases'])) {
                $prettyVersion = $installed['versions'][$packageName]['aliases'][0];
            } else {
                $prettyVersion = $installed['versions'][$packageName]['pretty_version'];
            }

            if ($withRef && isset($installed['versions'][$packageName]['reference'])) {
                return sprintf(
                    '%s@%s',
                    $prettyVersion,
                    substr($installed['versions'][$packageName]['reference'], 0, 7)
                );
            }
            return $prettyVersion;
        }

        throw new OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }
}
