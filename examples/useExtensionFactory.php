<?php declare(strict_types=1);

/**
 * Procedural example script to demonstrates how to access API v3.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowHandler;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

$container = require __DIR__ . '/bootstrap.php';

$handler = new ShowHandler($container->get(ExtensionFactory::class));

// Specify what components to display
$command = new ShowQuery(
    'core',
    false,
    true,
    true,
    true,
    true,
    true,
    true,
    true
);

/** @var Extension $extension */
$extension = $handler($command);

$releases = array_keys($extension->getReleases());
$iniEntries = array_keys($extension->getIniEntries());
$constants = array_keys($extension->getConstants());
$functions = array_keys($extension->getFunctions());
$classes = array_keys($extension->getClasses());
$interfaces = array_keys($extension->getInterfaces());
$classConstants = array_keys($extension->getClassConstants());
$methods = array_keys($extension->getMethods());


printf('# %s extension%s%s', $extension->getName(), PHP_EOL, PHP_EOL);

printf('## Summary%s%s', PHP_EOL, PHP_EOL);

printf('* Latest version supported : %s%s', $extension->getVersion(), PHP_EOL);
printf('* Releases : %d%s', count($releases), PHP_EOL);
printf('* INI Entries : %d%s', count($iniEntries), PHP_EOL);
printf('* Constants : %d%s', count($constants), PHP_EOL);
printf('* Functions : %d%s', count($functions), PHP_EOL);
printf('* Classes : %d%s', count($classes), PHP_EOL);
printf('* Interfaces : %d%s', count($interfaces), PHP_EOL);
printf('* Class constants : %d%s', count($classConstants), PHP_EOL);
printf('* Methods : %d%s%s', count($methods), PHP_EOL, PHP_EOL);

if ($command->isReleases()) {
    $results = print_r($releases, true);
    printf('## Releases : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isIni()) {
    $results = print_r($iniEntries, true);
    printf('## INI entries : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isConstants()) {
    $results = print_r($constants, true);
    printf('## Constants : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isFunctions()) {
    $results = print_r($functions, true);
    printf('## Functions : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isClasses()) {
    $results = print_r($classes, true);
    printf('## Classes : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isInterfaces()) {
    $results = print_r($interfaces, true);
    printf('## Interfaces : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isClassConstants()) {
    $results = print_r($classConstants, true);
    printf('## Class Constants : %s%s%s', $results, PHP_EOL, PHP_EOL);
}

if ($command->isMethods()) {
    $results = print_r($methods, true);
    printf('## Methods : %s%s%s', $results, PHP_EOL, PHP_EOL);
}
