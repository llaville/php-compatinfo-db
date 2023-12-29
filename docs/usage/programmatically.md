<!-- markdownlint-disable MD013 -->
# Programmatically

Repository contains an `examples` directory that have a good first to follow.
Here is another one that display only INI and CONSTANT from any extension supported by `CompatInfoDB`
that match a PHP minimum value.

```php
<?php declare(strict_types=1);

require_once __DIR__ . '/config/bootstrap.php';

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowHandler;
use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactoryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

// criteria
$extension = $_SERVER['argv'][1] ?? 'core';
$criteria = $_SERVER['argv'][2] ?? '7.0.0';

$container = (new ConsoleKernel('dev', true))->createFromConfigs([]);

$handler = new ShowHandler($container->get(ExtensionFactoryInterface::class));

$command = new ShowQuery(
    $extension,
    false,
    true,
    true,
    true,
    true,
    true,
    true,
    true,
    true,
    false
);

/** @var Extension $extension */
$extension = $handler($command);

$matches = [];
// extract only elements (ini, constant) that match $criteria
if ($command->isIni()) {
    $items = $extension->getIniEntries();
    $matches['ini'] = [];
    foreach ($items as $item) {
        if (version_compare($item->getPhpMin(), $criteria, 'ge')) {
            $matches['ini'][] = $item;
        }
    }
}
if ($command->isConstants()) {
    $items = $extension->getConstants();
    $matches['constant'] = [];
    foreach ($items as $item) {
        if (version_compare($item->getPhpMin(), $criteria, 'ge')) {
            $matches['constant'][] = $item;
        }
    }
}

printf('# Reference(s) found for extension : %s', $extension->getName());
echo PHP_EOL;

// print results that match PHP minimum $criteria
foreach ($matches as $kind => $items) {
    echo PHP_EOL;
    printf('## %s entries : %d', strtoupper($kind), count($items));
    echo PHP_EOL, PHP_EOL;
    foreach ($items as $item) {
        printf(
            '%s => PHP min %s%s' . PHP_EOL,
            $item->getName(),
            $item->getPhpMin(),
            empty($item->getPhpMax()) ? '' : ' => PHP max ' . $item->getPhpMax()
        );
    }
}
```
