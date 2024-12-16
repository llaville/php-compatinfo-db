<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowHandler;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactoryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

$container = require __DIR__ . '/bootstrap.php';

$handler = new ShowHandler($container->get(ExtensionFactoryInterface::class));

// Specify what components to display
$command = new ShowQuery(
    'imagick',
    false,
    true,
    true,
    true,
    true,
    true,
    true,
    true,
    true,
    false,
    false
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
$dependencies = $extension->getDependencies();


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
printf('* Methods : %d%s', count($methods), PHP_EOL);
printf('* Dependencies : %d%s', count($dependencies), PHP_EOL);
echo PHP_EOL;

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

if ($command->isDependencies()) {
    $results = [];
    foreach ($dependencies as $dependency) {
        $results[] = sprintf('%s: %s', $dependency->getName(), $dependency->getConstraint());
    }
    printf('## Dependencies : %s%s%s', var_export($results, true), PHP_EOL, PHP_EOL);
}
