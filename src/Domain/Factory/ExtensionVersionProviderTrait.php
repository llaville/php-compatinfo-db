<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Domain\Factory;

use function strtolower;
use function version_compare;
use const PHP_VERSION;

/**
 * enchant
 * @see https://github.com/php/php-src/commit/fb0902143291c8b605997a6b2a8f8717289a44d1
 *
 * fileinfo
 * @see https://github.com/php/php-src/commit/d6ccaef3e683976e6141b90ee1e315113f4a7baa
 *
 * filter
 * @see https://github.com/php/php-src/commit/2192e96ef53bc598cfe577368847df21d98e1c05
 *
 * ftp
 * @see https://github.com/php/php-src/commit/ec89c85054e44a0d4ea85f62601405843cd05d5d
 *
 * gmp
 * @see https://github.com/php/php-src/commit/2d78023244eaa5ec60b1325e530150394d625fa8
 *
 * hash
 * @see https://github.com/php/php-src/commit/3f96f01e9e4d50f47aa89da03853201304a58bba
 *
 * intl
 * @see https://github.com/php/php-src/commit/b1767d8a5625d7347e500e1230cf6c6c66d111ad
 *
 * json
 * @see https://github.com/php/php-src/commit/dee243d475b088189862d30755aac7bb9cdd61b3
 *
 * snmp
 * @see https://github.com/php/php-src/commit/70f41d1d9cb03f76f73e7a6099bfc7ce0c2b2701
 *
 * @since Release 3.0.0
 */
trait ExtensionVersionProviderTrait
{
    protected function getExtensionVersion(string $name): string
    {
        $normalizedName = strtolower($name);

        if ('enchant' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.0.26RC1', 'lt')) {
                return '1.1.0';
            }
        } elseif ('fileinfo' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.3.0alpha2', 'lt')) {
                return '1.0.5';
            }
        } elseif ('filter' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.0.0alpha1', 'lt')) {
                return '0.11.0';
            }
        } elseif ('ftp' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.0.0alpha1', 'lt')) {
                return '5.0.0';
            }
        } elseif ('gmp' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.0.0alpha1', 'lt')) {
                return '5.5.0';
            }
        } elseif ('hash' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.3.0alpha3', 'lt')) {
                return '1.0';
            }
        } elseif ('intl' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.3.0alpha2', 'lt')) {
                return '1.1.0';
            }
        } elseif ('json' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.4.0beta2', 'lt')) {
                return '1.6.0';
            }
        } elseif ('snmp' === $normalizedName) {
            if (version_compare(PHP_VERSION, '7.3.0alpha2', 'lt')) {
                return '0.1';
            }
        }

        $version = phpversion($normalizedName);
        $pattern = '/^[0-9]+\.[0-9]+/';
        if (false === $version || !preg_match($pattern, $version)) {
            /**
             * When version is not provided by the extension, or not standard format
             * or we don't have it in our reference (ex snmp) because have no sense
             * be sure at least to return latest PHP version supported.
             */
            $version = self::getLatestPhpVersion();
        }
        return $version;
    }

    protected function getLatestPhpVersion(string $phpVersion = PHP_VERSION): string
    {
        if (version_compare($phpVersion, '5.3', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_5_2;
        }
        if (version_compare($phpVersion, '5.4', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_5_3;
        }
        if (version_compare($phpVersion, '5.5', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_5_4;
        }
        if (version_compare($phpVersion, '5.6', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_5_5;
        }
        if (version_compare($phpVersion, '7.0', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_5_6;
        }
        if (version_compare($phpVersion, '7.1', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_7_0;
        }
        if (version_compare($phpVersion, '7.2', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_7_1;
        }
        if (version_compare($phpVersion, '7.3', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_7_2;
        }
        if (version_compare($phpVersion, '7.4', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_7_3;
        }
        if (version_compare($phpVersion, '8.0', 'lt')) {
            return ExtensionVersionProviderInterface::LATEST_PHP_7_4;
        }
        return ExtensionVersionProviderInterface::LATEST_PHP_8_0;
    }
}