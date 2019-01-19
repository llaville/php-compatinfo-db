<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb;

use PDO;

class DatabaseFactory
{
    private static $tempDir;
    private static $database = 'compatinfo.sqlite';

    public static function create(string $type)
    {
        switch ($type) {
            case 'sqlite':
                return static::createSqliteDb();

            default:
                throw new \LogicException('unknown database type ' . $type);
        }
    }

    public static function getDsn(string $type) : array
    {
        switch ($type) {
            case 'sqlite':
                return static::getSqliteDsn();

            default:
                throw new \LogicException('unknown database type ' . $type);
        }
    }

    private static function getSqliteDsn() : array
    {
        if (PATH_SEPARATOR == ';') {
            // windows
            $userHome = getenv('USERPROFILE');
        } else {
            // unix
            $userHome = getenv('HOME');
        }
        static::$tempDir = $userHome . '/.bartlett';

        return [
            'driver' => 'pdo_sqlite',
            'url' => 'sqlite:' . static::$tempDir . '/' . static::$database,
            'host' => 'localhost',
            'port' => '',
            'user' => 'root',
            'password' => '',
        ];
    }

    private static function createSqliteDb() : PDO
    {
        $dbParams = self::getSqliteDsn();

        if (!file_exists(static::$tempDir)) {
            mkdir(static::$tempDir);
        }
        $source = dirname(__DIR__) . '/data/' . static::$database;
        $dest   = static::$tempDir . '/' . static::$database;

        if (!file_exists($dest)
            || sha1_file($source) !== sha1_file($dest)
        ) {
            // install DB only if necessary (missing or modified)
            copy($source, $dest);
        }

        return new PDO($dbParams['url']);
    }
}