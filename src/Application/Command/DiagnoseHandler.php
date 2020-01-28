<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Laminas\Diagnostics\Check\Callback;
use Laminas\Diagnostics\Check\ExtensionLoaded;
use Laminas\Diagnostics\Check\PDOCheck;
use Laminas\Diagnostics\Check\PhpVersion;
use Laminas\Diagnostics\Result\Collection as ResultCollection;
use Laminas\Diagnostics\Result\Failure;
use Laminas\Diagnostics\Result\Success;
use Laminas\Diagnostics\Runner\Runner;

class DiagnoseHandler implements CommandHandlerInterface
{
    public function __invoke(DiagnoseCommand $command): ResultCollection
    {
        $runner = new Runner();

        $runner->addCheck(new PhpVersion('7.1.0'));

        $dbParams = $command->databaseParams;

        $runner->addCheck(new ExtensionLoaded($dbParams['driver']));

        if (strpos($dbParams['driver'], 'sqlite')) {
            $checkDbFile = new Callback(function() use ($dbParams) {
                $path = str_replace('sqlite:', '', $dbParams['url']);

                if (is_file($path) && is_readable($path)) {
                    return new Success(sprintf('DB file %s seems good', $path), $path);
                }
                return new Failure(sprintf('Something is wrong with DB file %s', $path), $path);
            });
            $runner->addCheck($checkDbFile);
        } else {
            list($dsnPrefix, ) = explode('://', $dbParams['url']);
            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s',
                $dsnPrefix,
                $dbParams['host'],
                $dbParams['port'],
                $dbParams['dbname']
            );
            $runner->addCheck(new PDOCheck($dsn, $dbParams['user'], $dbParams['password']));
        }

        return $runner->run();
    }
}
