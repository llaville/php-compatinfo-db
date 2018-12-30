<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use ZendDiagnostics\Check\Callback;
use ZendDiagnostics\Check\ExtensionLoaded;
use ZendDiagnostics\Check\PDOCheck;
use ZendDiagnostics\Check\PhpVersion;
use ZendDiagnostics\Result\Collection as ResultCollection;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Runner\Runner;

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
