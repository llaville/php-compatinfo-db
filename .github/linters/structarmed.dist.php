<?php

declare(strict_types=1);

/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Preset\Presets\DddPreset;

/**
 * StructArmed Configuration File for production
 *
 * @link https://boundwize.github.io/structarmed/configuration/
 * @since Release 6.34.0
 * @author Laurent Laville
 */

/**
 * Use a distribution baseline to adopt StructArmed gradually from codebase of version 6.34.0 that still have 47 violations
 * 38 in Application layer
 * 9 in Domain layer
 *
 * @link https://github.com/llaville/php-compatinfo-db/tree/6.34
 * @link https://boundwize.github.io/structarmed/baselines/
 */

return static function (Architecture $architecture): void {
    $architecture
        ->withPresets(
            Preset::PSR4(),
            Preset::CODEQUALITY(),
            Preset::DDD(),
        )
        // @see https://github.com/boundwize/structarmed/discussions/658 why we skip the rule
        ->skipRule(DddPreset::DOMAIN_NO_BASE_EXCEPTION)
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
        ])
        ->baseline(__DIR__ . '/structarmed-baseline.dist.php')
    ;
};
