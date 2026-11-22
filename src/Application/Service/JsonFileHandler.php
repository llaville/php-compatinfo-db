<?php

declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bartlett\CompatInfoDb\Application\Service;

use Boundwize\JsonRecast\JsonRecast;
use Boundwize\JsonRecast\Node\JsonDocument;
use Boundwize\JsonRecast\Parser\ParseError;

use RuntimeException;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function is_int;
use function json_decode;
use function json_encode;
use function sprintf;

use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

/**
 * JSON resource files handler.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class JsonFileHandler
{
    public function parse(string $filename): JsonDocument
    {
        if (!file_exists($filename)) {
            $error = sprintf('File %s does not exist.', $filename);
            throw new RuntimeException($error);
        }

        $jsonStr = file_get_contents($filename);

        try {
            $document = JsonRecast::parse($jsonStr);
        } catch (ParseError $error) {
            $error = sprintf(
                'Cannot decode file %s : invalid content at line %d, column %d ',
                $filename,
                $error->sourceLine,
                $error->column,
            );
            throw new RuntimeException($error);
        }

        return $document;
    }

    public function writeDocument(JsonDocument $document, string $filename): void
    {
        $jsonStr = JsonRecast::print($document);

        $bytes = file_put_contents($filename, $jsonStr);

        if (!$bytes) {
            $error = sprintf('File %s cannot be updated.', $filename);
            throw new RuntimeException($error);
        }
    }

    /**
     * @return array<string, string>|array<int, mixed>|null
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
     * @param array<int, mixed> $data
     */
    public function write(string $path, string $fileBasename, string $ver, array $data): bool
    {
        $filename = implode(DIRECTORY_SEPARATOR, [$path, $ver, $fileBasename . '.json']);

        if (!file_exists($filename)) {
            return false;
        }
        $jsonStr = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        $bytes = file_put_contents($filename, $jsonStr);
        return is_int($bytes);
    }
}
