<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY())
    ->layer('Domain', 'src/Domain/')
    // the kernel is the composition root, so it is carved out of Application
    ->layer('Application', 'src/Application/', 'src/Application/Kernel/')
    ->layer('Kernel', 'src/Application/Kernel/')
    // Doctrine entities are plain data classes, also consumed by the Domain
    ->layer('Infrastructure', 'src/Infrastructure/', 'src/Infrastructure/Persistence/Doctrine/Entity/')
    ->layer('Entity', 'src/Infrastructure/Persistence/Doctrine/Entity/')
    ->layer('Presentation', 'src/Presentation/')
    ->ruleset([
        'Entity'         => [],
        'Domain'         => ['Entity'],
        'Application'    => ['+Domain', 'Infrastructure', 'Presentation'],
        'Infrastructure' => ['+Domain', 'Application', 'Kernel'],
        'Presentation'   => ['+Domain', 'Application', 'Infrastructure'],
        'Kernel'         => ['Application', 'Infrastructure', 'Presentation'],
    ]);
