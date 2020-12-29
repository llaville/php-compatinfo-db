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


printf('# %s extension', $extension->getName()); echo PHP_EOL, PHP_EOL;

print('## Summary'); echo PHP_EOL, PHP_EOL;

printf('* Latest version supported : %s', $extension->getVersion()); echo PHP_EOL;
printf('* Releases : %d', count($releases)); echo PHP_EOL;
printf('* INI Entries : %d', count($iniEntries)); echo PHP_EOL;
printf('* Constants : %d', count($constants)); echo PHP_EOL;
printf('* Functions : %d', count($functions)); echo PHP_EOL;
printf('* Classes : %d', count($classes)); echo PHP_EOL;
printf('* Interfaces : %d', count($interfaces)); echo PHP_EOL;
printf('* Class constants : %d', count($classConstants)); echo PHP_EOL;
printf('* Methods : %d', count($methods)); echo PHP_EOL, PHP_EOL;

if ($command->isReleases()) {
    $results = print_r($releases, true);
    printf('## Releases : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isIni()) {
    $results = print_r($iniEntries, true);
    printf('## INI entries : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isConstants()) {
    $results = print_r($constants, true);
    printf('## Constants : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isFunctions()) {
    $results = print_r($functions, true);
    printf('## Functions : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isClasses()) {
    $results = print_r($classes, true);
    printf('## Classes : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isInterfaces()) {
    $results = print_r($interfaces, true);
    printf('## Interfaces : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isClassConstants()) {
    $results = print_r($classConstants, true);
    printf('## Class Constants : %s', $results); echo PHP_EOL, PHP_EOL;
}

if ($command->isMethods()) {
    $results = print_r($methods, true);
    printf('## Methods : %s', $results); echo PHP_EOL, PHP_EOL;
}
