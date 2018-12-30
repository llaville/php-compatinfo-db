<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\ExtensionFactory;

class ShowHandler implements CommandHandlerInterface
{
    public function __invoke(ShowCommand $command): array
    {
        $reference = new ExtensionFactory($command->getExtension());
        $results = [];
        $summary = [];

        $raw = $reference->getReleases();
        $summary['releases'] = count($raw);
        if ($command->isReleases()) {
            $results['releases'] = $raw;
        }

        $raw = $reference->getIniEntries();
        $summary['iniEntries'] = count($raw);
        if ($command->isIni()) {
            $results['iniEntries'] = $raw;
        }

        $raw = $reference->getConstants();
        $summary['constants'] = count($raw);
        if ($command->isConstants()) {
            $results['constants'] = $raw;
        }

        $raw = $reference->getFunctions();
        $summary['functions'] = count($raw);
        if ($command->isFunctions()) {
            $results['functions'] = $raw;
        }

        $raw = $reference->getInterfaces();
        $summary['interfaces'] = count($raw);
        if ($command->isInterfaces()) {
            $results['interfaces'] = $raw;
        }

        $raw = $reference->getClasses();
        $summary['classes'] = count($raw);
        if ($command->isClasses()) {
            $results['classes'] = $raw;
        }

        $raw = $reference->getClassConstants();
        $summary['class-constants'] = 0;
        foreach ($raw as $values) {
            $summary['class-constants'] += count($values);
        }
        if ($command->isClassConstants()) {
            $results['class-constants'] = $raw;
        }

        $raw = $reference->getClassMethods();
        $summary['methods'] = 0;
        foreach ($raw as $values) {
            $summary['methods'] += count($values);
        }
        if ($command->isMethods()) {
            $results['methods'] = $raw;
        }

        $raw = $reference->getClassStaticMethods();
        $summary['static methods'] = 0;
        foreach ($raw as $values) {
            $summary['static methods'] += count($values);
        }
        if ($command->isMethods()) {
            $results['static methods'] = $raw;
        }

        if (empty($results)) {
            $results = ['summary' => $summary];
        }

        return $results;
    }
}
