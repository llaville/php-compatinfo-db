<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Bartlett\CompatInfoDb\ExtensionFactory;

$dump = false;

$name = 'imagick';
$ext  = new ExtensionFactory($name);

printf('== Latest PHP platforms supported');
echo PHP_EOL;
foreach (array('5.2', '5.3', '5.4', '5.5', '5.6', '7.0', '7.1', '7.2', '7.3') as $phpVersion) {
    printf('= PHP %s : %s', $phpVersion, ExtensionFactory::getLatestPhpVersion($phpVersion));
    echo PHP_EOL;
}
echo PHP_EOL;

printf('== Details of %s extension', $name);
echo PHP_EOL;

printf('= Current version : %s', $ext->getCurrentVersion());
echo PHP_EOL;

printf('= Meta version : %s', var_export($ext->getMetaVersion(), true));
echo PHP_EOL;

$results = $dump ? print_r($ext->getReleases(), true) : count($ext->getReleases());
printf('= Releases : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getInterfaces(), true) : count($ext->getInterfaces());
printf('= Interfaces : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getClasses(), true) : count($ext->getClasses());
printf('= Classes : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getFunctions(), true) : count($ext->getFunctions());
printf('= Functions : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getConstants(), true) : count($ext->getConstants());
printf('= Constants : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getIniEntries(), true) : count($ext->getIniEntries());
printf('= INI entries : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getClassConstants(), true) : count($ext->getClassConstants());
printf('= Class Constants : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getClassStaticMethods(), true) : count($ext->getClassStaticMethods());
printf('= Class Static Methods : %s', $results);
echo PHP_EOL;

$results = $dump ? print_r($ext->getClassMethods(), true) : count($ext->getClassMethods());
printf('= Class Methods : %s', $results);
echo PHP_EOL;
