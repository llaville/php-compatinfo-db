<?php declare(strict_types=1);

/**
 * JSON resource files handler.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Service;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function is_int;
use function json_decode;
use function json_encode;
use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

/**
 * @since Release 2.0.0RC1
 */
final class JsonFileHandler
{
    /**
     * @param string $path
     * @param string $fileBasename
     * @param string $ver
     * @return array<string, string>|array<int, array>|null
     */
    public function read(string $path, string $fileBasename, string $ver): ?array
    {
        if ('extensions' === $fileBasename) {
            $filename = $path;
        } else {
            $filename = $path . DIRECTORY_SEPARATOR . $ver;
        }
        $filename .= DIRECTORY_SEPARATOR . $fileBasename . '.json';

        if (!file_exists($filename)) {
            return null;
        }
        $jsonStr = file_get_contents($filename);
        return json_decode($jsonStr, true);
    }

    /**
     * @param string $path
     * @param string $fileBasename
     * @param string $ver
     * @param array<int, array> $data
     * @return bool
     */
    public function write(string $path, string $fileBasename, string $ver, array $data): bool
    {
        $filename = implode(DIRECTORY_SEPARATOR, [$path, $ver, $fileBasename . '.json']);

        if (!file_exists($filename)) {
            return false;
        }
        $jsonStr = json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
        $bytes = file_put_contents($filename, $jsonStr);
        return is_int($bytes);
    }
}
