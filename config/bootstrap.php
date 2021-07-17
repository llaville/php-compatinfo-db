<?php

if (\Phar::running()) {
    $possibleAutoloadPaths = [
        'phar://compatinfo-db.phar/vendor/autoload.php'
    ];
} else {
    $possibleAutoloadPaths = [
        // local dev repository
        __DIR__ . '/../vendor/autoload.php',
        // dependency
        __DIR__ . '/../../../../vendor/autoload.php',
    ];
}

$isAutoloadFound = false;
foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
    if (file_exists($possibleAutoloadPath)) {
        require_once $possibleAutoloadPath;
        $isAutoloadFound = true;
        break;
    }
}

if ($isAutoloadFound === false) {
    throw new RuntimeException(sprintf(
        'Unable to find "config/bootstrap.php" in "%s" paths.',
        implode('", "', $possibleAutoloadPaths)
    ));
}

use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;

putenv('APP_ENV=' . ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'prod'));
putenv('APP_PROXY_DIR=' . ($_SERVER['APP_PROXY_DIR'] ?? $_ENV['APP_PROXY_DIR'] ?? '/tmp/bartlett/php-compatinfo-db/' . ApplicationInterface::VERSION . '/proxies'));
putenv('SYMFONY_DEPRECATIONS_HELPER=' . ($_SERVER['SYMFONY_DEPRECATIONS_HELPER'] ?? $_ENV['SYMFONY_DEPRECATIONS_HELPER'] ?? 'disabled'));
