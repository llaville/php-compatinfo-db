<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Service;

class JsonFileHandler
{
    /** @var string  */
    private $baseDir;

    public function __construct(string $refDir)
    {
        $this->baseDir = $refDir;
    }

    public function read(string $refName, string $ext, string $major) : array
    {
        $filename = $this->baseDir . '/' . ucfirst($refName) . $major . ".$ext.json";

        if (!file_exists($filename)) {
            return [];
        }
        $jsonStr = file_get_contents($filename);
        return json_decode($jsonStr, true);
    }

    public function write(string $refName, string $ext, string $major, array $data) : bool
    {
        $filename = $this->baseDir . '/' . ucfirst($refName) . $major . ".$ext.json";

        if (!file_exists($filename)) {
            return false;
        }
        $jsonStr = json_encode($data, JSON_PRETTY_PRINT);
        $bytes = file_put_contents($filename, $jsonStr);
        return is_int($bytes);
    }
}
