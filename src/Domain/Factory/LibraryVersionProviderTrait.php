<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Factory;

use Exception;
use ReflectionClassConstant;
use function constant;
use function dechex;
use function defined;
use function function_exists;
use function method_exists;
use function ord;
use function phpversion;
use function preg_match;
use function preg_replace;
use function preg_replace_callback;
use function sprintf;
use function str_split;
use function strlen;
use function vsprintf;

/**
 * curl
 * @link https://www.php.net/manual/en/book.curl
 * @link https://curl.haxx.se/
 *
 * iconv
 * @link https://www.php.net/manual/en/book.iconv.php
 * @link http://www.gnu.org/software/libiconv/
 *
 * intl
 * @link https://www.php.net/manual/en/book.intl.php
 * @link http://site.icu-project.org/
 *
 * imagick
 * @link https://www.php.net/manual/en/book.imagick.php
 * @link http://www.imagemagick.org/
 *
 * libmemcached
 * @link https://www.php.net/manual/en/book.memcached.php
 * @link https://libmemcached.org/
 *
 * libxml
 * @link https://www.php.net/manual/en/book.libxml.php
 * @link http://www.xmlsoft.org/
 *
 * openssl
 * @link https://www.php.net/manual/en/book.openssl.php
 * @link https://www.openssl.org/
 * @link https://www.libressl.org/
 *
 * pcre
 * @link https://www.php.net/manual/en/book.pcre
 *
 * sqlite3
 * @link https://www.php.net/manual/en/book.sqlite3.php
 * @link https://sqlite.org/
 *
 * xsl
 * @link https://www.php.net/manual/en/book.xsl.php
 * @link https://www.php.net/manual/en/book.libxml.php
 *
 * zip
 * @link https://www.php.net/manual/en/book.zip.php
 * @link https://libzip.org/
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
trait LibraryVersionProviderTrait
{
    private function getPrettyVersion(string $name): string
    {
        $versionText = '';

        switch ($name) {
            case 'libcurl':
                if (function_exists('curl_version')) {
                    $meta = curl_version();
                    $versionText = $meta['version'];
                }
                break;
            case 'enchant':
                if ($constant = $this->constantExists('LIBENCHANT_VERSION')) {
                    $versionText = $constant;
                }
                break;
            case 'iconv':
                $versionText = $this->constantExists('ICONV_VERSION');
                break;
            case 'intl':
            case 'ICU':
                if ($constant = $this->constantExists('INTL_ICU_VERSION')) {
                    $versionText = $constant;
                }
                break;
            case 'imagick':
                if (method_exists('Imagick', 'getVersion')) {
                    $meta = \Imagick::getVersion();
                    preg_match('/^ImageMagick ([\d.]+)(?:-(\d+))?/', $meta['versionString'], $matches);
                    if (isset($matches[2])) {
                        $versionText = "{$matches[1]}.{$matches[2]}";
                    } else {
                        $versionText = $matches[1];
                    }
                }
                break;
            case 'libmemcached':
                if ($constant = $this->constantExists('LIBMEMCACHED_VERSION_HEX', 'Memcached')) {
                    // since release 2.2.0b1
                    // @link https://github.com/php-memcached-dev/php-memcached/commit/a63c8f08fdae80a6d9c4050eb3b126c1e5b05fe7
                    $constant = sprintf('%09s', dechex($constant));
                    $parts = str_split($constant, 3);
                    $versionText = vsprintf('%d.%d.%d', $parts);
                }
                break;
            case 'libxml':
                if ($constant = $this->constantExists('LIBXML_DOTTED_VERSION')) {
                    $versionText = $constant;
                }
                break;
            case 'openssl':
                if ($constant = $this->constantExists('OPENSSL_VERSION_TEXT')) {
                    $versionText = preg_replace_callback('{^(?:OpenSSL|LibreSSL)?\s*([0-9.]+)([a-z]*).*}i', function ($match) {
                        if (empty($match[2])) {
                            return $match[1];
                        }

                        // OpenSSL versions add another letter when they reach Z.
                        // e.g. OpenSSL 0.9.8zh 3 Dec 2015
                        if (!preg_match('{^z*[a-z]$}', $match[2])) {
                            // 0.9.8abc is garbage
                            return '0';
                        }

                        $len = strlen($match[2]);
                        $patchVersion = ($len - 1) * 26; // All Z
                        $patchVersion += ord($match[2][$len - 1]) - 96;
                        return $match[1] . '.' . $patchVersion;
                    }, $constant);
                }
                break;
            case 'pcre':
                if ($constant = $this->constantExists('PCRE_VERSION')) {
                    $versionText = preg_replace('{^(\S+).*}', '$1', $constant);
                }
                break;
            case 'libpq':
                if ($constant = $this->constantExists('PGSQL_LIBPQ_VERSION')) {
                    // @see https://github.com/php/php-src/commit/eae893bd3e426ea7f9fcf42b715efb1e49f055ab
                    $versionText = $constant;
                }
                break;
            case 'sqlite3':
                if (method_exists('sqlite3', 'version')) {
                    $meta = \SQLite3::version();
                    $versionText = $meta['versionString'];
                }
                break;
            case 'xsl':
                if ($constant = $this->constantExists('LIBXSLT_DOTTED_VERSION')) {
                    $versionText = $constant;
                }
                break;
            case 'libzip':
                if ($constant = $this->constantExists('LIBZIP_VERSION', 'ZipArchive')) {
                    $versionText = $constant;
                } else {
                    $versionText = phpversion('zip');
                }
                break;
            case 'librdkafka':
                if ($constant = $this->constantExists('RD_KAFKA_VERSION')) {
                    // @see https://github.com/arnaud-lb/php-rdkafka/issues/232
                    $major = (RD_KAFKA_VERSION & 0xFF000000) >> 24;
                    $minor = (RD_KAFKA_VERSION & 0x00FF0000) >> 16;
                    $patch = (RD_KAFKA_VERSION & 0x0000FF00) >> 8;
                    $versionText = sprintf('%d.%d.%d', $major, $minor, $patch);
                }
                break;
        }

        return $versionText;
    }

    /**
     * Checks if the constant or class constant exists, and return its value.
     *
     * @param string $name
     * @param string|null $class
     * @return mixed
     */
    private function constantExists(string $name, ?string $class = null)
    {
        if (null === $class) {
            return defined($name) ? constant($name) : null;
        }

        try {
            return (new ReflectionClassConstant($class, $name))->getValue();
        } catch (Exception $e) {
            return null;
        }
    }
}
