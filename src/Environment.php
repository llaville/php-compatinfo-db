<?php

declare(strict_types=1);

/**
 * Application Environment.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link     http://php5.laurent-laville.org/compatinfo/
 */

namespace Bartlett\CompatInfoDb;

use PDO;

/**
 * Application Environment.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link     http://php5.laurent-laville.org/compatinfo/
 * @since    Class available since Release 3.6.0 of PHP_CompatInfo
 * @since    Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class Environment
{
    const PHP_MIN = '5.4.0';

    /**
     * Initializes installation of the Reference database
     *
     * @return PDO Instance of pdo_sqlite
     */
    public static function initRefDb()
    {
        $database = 'compatinfo.sqlite';

        if (PATH_SEPARATOR == ';') {
            // windows
            $userHome = getenv('USERPROFILE');
        } else {
            // unix
            $userHome = getenv('HOME');
        }
        $tempDir = $userHome . '/.bartlett';

        if (!file_exists($tempDir)) {
            mkdir($tempDir);
        }
        $source = dirname(__DIR__) . '/data/' . $database;
        $dest   = $tempDir . '/' . $database;

        if (!file_exists($dest)
            || sha1_file($source) !== sha1_file($dest)
        ) {
            // install DB only if necessary (missing or modified)
            copy($source, $dest);
        }

        $pdo = new PDO('sqlite:' . $tempDir . '/' . $database);
        return $pdo;
    }

    /**
     * Gets version informations about the Reference database
     *
     * @return array
     */
    public static function versionRefDb()
    {
        $pdo = self::initRefDb();

        $stmt = $pdo->prepare(
            'SELECT build_string as "build.string", build_date as "build.date", build_version as "build.version"' .
            ' FROM bartlett_compatinfo_versions'
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Checks the minimum requirements on current platform for the phar distribution
     *
     * @throws \RuntimeException when min requirements does not match
     */
    public static function checkRequirements()
    {
        $error = '';

        if (version_compare(PHP_VERSION, self::PHP_MIN, '<')) {
            $error .= sprintf(
                "\n- Expected PHP %s or above, actual version is %s",
                self::PHP_MIN,
                PHP_VERSION
            );
        }

        $ext = 'pdo_sqlite';
        if (!extension_loaded($ext)) {
            $error .= sprintf(
                "\n- Expected PHP extension %s loaded to use SQLite DataBase, extension may be missing",
                $ext
            );
        }

        if (!empty($error)) {
            throw new \RuntimeException(
                'Your platform does not satisfy CompatInfo minimum requirements' .
                $error
            );
        }
    }
}
