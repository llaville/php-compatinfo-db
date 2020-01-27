<?php

declare(strict_types=1);

/**
 * Extension Factory.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link     http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\CompatInfoDb;

/**
 * Extension factory to build a concrete Reference instance with all releases,
 * independent from the platform.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link     http://php5.laurent-laville.org/compatinfo/
 * @since    Class available since Release 4.0.0-alpha2 of PHP_CompatInfo
 * @since    Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class ExtensionFactory implements ReferenceInterface
{
    const LATEST_PHP_5_2 = '5.2.17';
    const LATEST_PHP_5_3 = '5.3.29';
    const LATEST_PHP_5_4 = '5.4.45';
    const LATEST_PHP_5_5 = '5.5.38';
    const LATEST_PHP_5_6 = '5.6.40';
    const LATEST_PHP_7_0 = '7.0.33';
    const LATEST_PHP_7_1 = '7.1.33';
    const LATEST_PHP_7_2 = '7.2.26';
    const LATEST_PHP_7_3 = '7.3.14';
    const LATEST_PHP_7_4 = '7.4.2';

    protected $storage;

    private $name;

    /**
     * Creates a new extension reference
     *
     * @param string $name Name of extension
     */
    public function __construct(string $name)
    {
        $this->storage = new SqliteStorage($name);
        $this->name    = $name;
    }

    /**
     * Returns name of current extension
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    public function getMetaVersion(?string $key = null, ?string $extname = null) : array
    {
        $meta = [];

        if (in_array('curl', array($this->name, $extname))
            && function_exists('curl_version')
        ) {
            $meta = curl_version();
            $meta['version_text'] = $meta['version'];

        } elseif (in_array('libxml', array($this->name, $extname))) {
            $meta = array(
                'version_number' => LIBXML_VERSION,
                'version_text'   => LIBXML_DOTTED_VERSION,
            );

        } elseif (in_array('intl', array($this->name, $extname))) {
            $meta = array(
                'version_number' => defined('INTL_ICU_VERSION')
                    ? INTL_ICU_VERSION : false,
                'version_text'   => defined('INTL_ICU_VERSION')
                    ? INTL_ICU_VERSION : false,
            );

        } elseif (in_array('openssl', array($this->name, $extname))) {
            $meta = array(
                'version_number' => defined('OPENSSL_VERSION_NUMBER')
                    ? OPENSSL_VERSION_NUMBER : false,
                'version_text'   => defined('OPENSSL_VERSION_TEXT')
                    ? OPENSSL_VERSION_TEXT : false,
            );

        } elseif (in_array('imagick', array($this->name, $extname))) {
            if (method_exists('Imagick', 'getVersion')) {
                $v = \Imagick::getVersion();
                if (preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $v['versionString'], $matches)) {
                    $meta = array(
                        'version_number' => $v['versionNumber'],
                        'version_text'   => $matches[1],
                    );
                }
            }

        } elseif (in_array('sqlite3', array($this->name, $extname))) {
            if (method_exists('sqlite3', 'version')) {
                $v = \SQLite3::version();
                $meta = array(
                    'version_number' => $v['versionNumber'],
                    'version_text'   => $v['versionString'],
                );
            }
        }

        if (isset($key) && array_key_exists($key, $meta)) {
            return $meta[$key];
        }
        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentVersion() : string
    {
        return $this->getVersion($this->name);
    }

    private function getVersion(string $name) : string
    {
        $version = phpversion($name);
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

    /**
     * {@inheritdoc}
     */
    public function getLatestVersion() : string
    {
        if (!empty($this->version)) {
            return $this->version;
        }
        return $this->getLatestPhpVersion();
    }

    public static function getLatestPhpVersion($phpVersion = PHP_VERSION) : string
    {
        if (version_compare($phpVersion, '5.3', 'lt')) {
            return self::LATEST_PHP_5_2;
        }
        if (version_compare($phpVersion, '5.4', 'lt')) {
            return self::LATEST_PHP_5_3;
        }
        if (version_compare($phpVersion, '5.5', 'lt')) {
            return self::LATEST_PHP_5_4;
        }
        if (version_compare($phpVersion, '5.6', 'lt')) {
            return self::LATEST_PHP_5_5;
        }
        if (version_compare($phpVersion, '7.0', 'lt')) {
            return self::LATEST_PHP_5_6;
        }
        if (version_compare($phpVersion, '7.1', 'lt')) {
            return self::LATEST_PHP_7_0;
        }
        if (version_compare($phpVersion, '7.2', 'lt')) {
            return self::LATEST_PHP_7_1;
        }
        if (version_compare($phpVersion, '7.3', 'lt')) {
            return self::LATEST_PHP_7_2;
        }
        if (version_compare($phpVersion, '7.4', 'lt')) {
            return self::LATEST_PHP_7_3;
        }
        return self::LATEST_PHP_7_4;
    }

    /**
     * {@inheritdoc}
     */
    public function getReleases() : array
    {
        return $this->storage->getMetaData('releases');
    }

    /**
     * {@inheritdoc}
     */
    public function getInterfaces() : array
    {
        return $this->storage->getMetaData('interfaces');
    }

    /**
     * {@inheritdoc}
     */
    public function getClasses() : array
    {
        return $this->storage->getMetaData('classes');
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() : array
    {
        return $this->storage->getMetaData('functions');
    }

    /**
     * {@inheritdoc}
     */
    public function getConstants() : array
    {
        return $this->storage->getMetaData('constants');
    }

    /**
     * {@inheritdoc}
     */
    public function getIniEntries() : array
    {
        return $this->storage->getMetaData('iniEntries');
    }

    /**
     * {@inheritdoc}
     */
    public function getClassConstants() : array
    {
        return $this->storage->getMetaData('classConstants');
    }

    /**
     * {@inheritdoc}
     */
    public function getClassStaticMethods() : array
    {
        return $this->storage->getMetaData('classMethods', true);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMethods() : array
    {
        return $this->storage->getMetaData('classMethods', false);
    }

    public function getExtensions() : array
    {
        $records = $this->storage->getMetaData('extensions');

        $rows = array();

        foreach ($records as $rec) {
            $key = strtolower($rec['name']);

            if (!empty($rec['date']) && !array_key_exists($key, $rows)) {
                $ref = new \stdClass;
                $ref->name    = $rec['name'];
                $ref->version = $rec['ext.min'];
                $ref->state   = $rec['state'];
                $ref->date    = $rec['date'];

                if (extension_loaded($ref->name)) {
                    $version = $this->getVersion($ref->name);
                } else {
                    $version = '';
                }
                $ref->loaded   = $version;
                $ref->outdated = version_compare($ref->version, $version, 'gt') ;

                $rows[$key] = $ref;
            }
        }

        ksort($rows);
        return array_values($rows);
    }
}
