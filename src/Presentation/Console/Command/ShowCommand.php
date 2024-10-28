<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;
use Bartlett\CompatInfoDb\Domain\ValueObject\IniEntry;
use Bartlett\CompatInfoDb\Domain\ValueObject\Release;
use Bartlett\CompatInfoDb\Presentation\Console\Style;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_unique;
use function array_unshift;
use function count;
use function explode;
use function implode;
use function ksort;
use function method_exists;
use function sprintf;
use function trim;
use const SORT_NATURAL;

/**
 * Show details of a reference (extension) supported.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ShowCommand extends AbstractCommand implements CommandInterface
{
    use LibraryVersionProviderTrait;

    public const NAME = 'db:show';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Show details of a reference supported in the Database')
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                'extension to extract components (case insensitive)'
            )
            ->addOption('releases', null, null, 'Show releases')
            ->addOption('ini', null, null, 'Show ini Entries')
            ->addOption('constants', null, null, 'Show constants')
            ->addOption('functions', null, null, 'Show functions')
            ->addOption('interfaces', null, null, 'Show interfaces')
            ->addOption('classes', null, null, 'Show classes')
            ->addOption('methods', null, null, 'Show methods')
            ->addOption('class-constants', null, null, 'Show class constants')
            ->addOption('dependencies', null, null, 'Show dependency constraints')
            ->addOption('polyfills', null, null, 'Show polyfills supported')
            ->addOption('deprecations', null, null, 'Show deprecated components')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $showQuery = new ShowQuery(
            trim($input->getArgument('extension')),
            $input->getOption('releases'),
            $input->getOption('ini'),
            $input->getOption('constants'),
            $input->getOption('functions'),
            $input->getOption('interfaces'),
            $input->getOption('classes'),
            $input->getOption('methods'),
            $input->getOption('class-constants'),
            $input->getOption('dependencies'),
            $input->getOption('polyfills'),
            $input->getOption('deprecations')
        );

        /** @var Extension|null $extension */
        $extension = $this->queryBus->query($showQuery);

        $io = new Style($input, $output);

        if (null === $extension) {
            $io->error(sprintf('Extension "%s" is not available', $showQuery->getExtension()));
            return self::FAILURE;
        }

        $summary = true;

        if ($showQuery->isReleases()) {
            $summary = false;
            $this->formatSection($extension->getReleases(), 'Releases', $io);
        }
        if ($showQuery->isIni()) {
            $summary = false;
            $this->formatSection($extension->getIniEntries(), 'Ini Entries', $io);
        }
        if ($showQuery->isConstants()) {
            $summary = false;
            $this->formatSection($extension->getConstants(), 'Constants', $io);
        }
        if ($showQuery->isFunctions()) {
            $summary = false;
            $this->formatSection($extension->getFunctions(), 'Functions', $io);
        }
        if ($showQuery->isClasses()) {
            $summary = false;
            $this->formatSection($extension->getClasses(), 'Classes', $io);
        }
        if ($showQuery->isInterfaces()) {
            $summary = false;
            $this->formatSection($extension->getInterfaces(), 'Interfaces', $io);
        }
        if ($showQuery->isClassConstants()) {
            $summary = false;
            $this->formatSection($extension->getClassConstants(), 'Class Constants', $io);
        }
        if ($showQuery->isMethods()) {
            $summary = false;
            $this->formatSection($extension->getMethods(), 'Methods', $io);
        }
        if ($showQuery->isDependencies()) {
            $summary = false;
            $this->formatDependency($extension->getDependencies(), $io);
        }
        if ($showQuery->isPolyfills()) {
            $summary = false;
            $this->formatPolyfills($extension, $io);
        }
        if ($showQuery->isDeprecations()) {
            $summary = false;
            $this->formatDeprecations($extension, $io);
        }

        if (!$summary) {
            return self::SUCCESS;
        }

        $io->title('Reference Summary');
        $io->columns(
            count($extension->getReleases()),
            '  Releases                                  %10d'
        );
        $configs = $extension->getIniEntries();
        $io->columns(
            count($configs),
            '  INI entries                               %10d'
        );
        $constants = $extension->getConstants();
        $io->columns(
            count($constants),
            '  Constants                                 %10d'
        );
        $functions = $extension->getFunctions();
        $io->columns(
            count($functions),
            '  Functions                                 %10d'
        );
        $classes = $extension->getClasses();
        $io->columns(
            count($classes),
            '  Classes                                   %10d'
        );
        $interfaces = $extension->getInterfaces();
        $io->columns(
            count($interfaces),
            '  Interfaces                                %10d'
        );
        $classConstants = $extension->getClassConstants();
        $io->columns(
            count($classConstants),
            '  Class Constants                           %10d'
        );
        $io->columns(
            count($extension->getMethods()),
            '  Methods                                   %10d'
        );
        $dependencies = [];
        foreach ($extension->getDependencies() as $dependency) {
            $dependencies[] = $dependency->getName();
        }
        $dependencies = array_unique($dependencies);
        $io->columns(
            count($dependencies),
            '  Dependencies                              %10d'
        );
        $polyfills = [];
        foreach ([$functions, $constants, $classes] as $components) {
            foreach ($components as $name => $valueObject) {
                if (!empty($valueObject->getPolyfill())) {
                    foreach (explode(', ', $valueObject->getPolyfill()) as $package) {
                        $polyfills[] = $package;
                    }
                }
            }
        }
        $polyfills = array_unique($polyfills);
        $io->columns(
            count($polyfills),
            '  Polyfills                                 %10d'
        );
        $deprecations = [];
        foreach ([$functions, $constants, $configs, $classes, $interfaces, $classConstants] as $components) {
            foreach ($components as $name => $valueObject) {
                if (method_exists($valueObject, 'getDeprecated')) {
                    if (!empty($valueObject->getDeprecated())) {
                        $deprecations[] = $name;
                    }
                }
            }
        }
        $deprecations = array_unique($deprecations);
        $io->columns(
            count($deprecations),
            '  Deprecations                              %10d'
        );

        return self::SUCCESS;
    }

    private function formatPolyfills(Extension $extension, StyleInterface $io): void
    {
        $functions = $extension->getFunctions();
        $constants = $extension->getConstants();
        $classes   = $extension->getClasses();

        $polyfills = [];

        foreach ([$functions, $constants, $classes] as $components) {
            foreach ($components as $name => $valueObject) {
                if (!empty($valueObject->getPolyfill())) {
                    foreach (explode(', ', $valueObject->getPolyfill()) as $package) {
                        if ($valueObject instanceof Function_) {
                            $type = 'functions';
                        } elseif ($valueObject instanceof Constant_) {
                            $type = 'constants';
                        } else {
                            $type = 'classes';
                        }
                        $polyfills[$package][$type][] = $name;
                    }
                }
            }
        }
        ksort($polyfills, SORT_NATURAL);

        $io->title('Polyfills');

        foreach ($polyfills as $package => $values) {
            $io->section($package);

            foreach ($values as $type => $names) {
                $io->listing([$type], ['type' => ' > ', 'style' => 'fg=green', 'indent' => '']);
                $io->listing($names, []);
            }
        }
    }

    private function formatDeprecations(Extension $extension, StyleInterface $io): void
    {
        $functions      = $extension->getFunctions();
        $constants      = $extension->getConstants();
        $configs        = $extension->getIniEntries();
        $classes        = $extension->getClasses();
        $interfaces     = $extension->getInterfaces();
        $classConstants = $extension->getClassConstants();

        $deprecations = [];

        foreach ([$functions, $constants, $configs, $classes, $interfaces, $classConstants] as $components) {
            foreach ($components as $name => $valueObject) {
                $deprecation = $valueObject->getDeprecated();
                if (!empty($deprecation)) {
                    if ($valueObject instanceof Function_) {
                        $declaringClass = $valueObject->getDeclaringClass();
                        $type = empty($declaringClass) ? 'functions' : 'methods';
                    } elseif ($valueObject instanceof Constant_) {
                        $declaringClass = $valueObject->getDeclaringClass();
                        $type = empty($declaringClass) ? 'constants' : 'class constants';
                    } elseif ($valueObject instanceof IniEntry) {
                        $type = 'configuration';
                    } elseif ($valueObject instanceof Class_) {
                        $type = $valueObject->isInterface() ? 'interfaces' : 'classes';
                    } else {
                        continue;
                    }
                    $deprecations[$type][] = $name . ' ' . $deprecation;
                }
            }
        }

        $io->title('Deprecations');

        foreach ($deprecations as $type => $messages) {
            $io->listing([$type], ['type' => ' > ', 'style' => 'fg=green', 'indent' => '']);
            $io->listing($messages, []);
        }
    }

    /**
     * @param Dependency[] $data
     */
    private function formatDependency(array $data, StyleInterface $io): void
    {
        $rows = [];
        $failures = 0;
        foreach ($data as $domain) {
            $name = $domain->getName();
            $ver = $this->getPrettyVersion($name);
            $constraint = $domain->getConstraint();
            $verified = $ver !== '' && Semver::satisfies($ver, $constraint);
            $rows[$constraint] = [$name, $verified ? $constraint : '<error>' . $constraint . '</error>', $verified ? 'Y' : 'N'];
            if (!$verified) {
                $failures++;
            }
        }

        $io->section('Dependencies');

        $headers = ['Library', 'Constraint', 'Satisfied'];
        $footers = [
            '<info>Total</info>',
            sprintf('<info>[%d]</info>', count($rows)),
            sprintf('<info>[%d/%d]</info>', count($rows) - $failures, count($rows)),
        ];
        $rows[] = new TableSeparator();
        $rows[] = $footers;
        $io->table($headers, $rows);
    }

    /**
     * @param array<object> $data
     */
    private function formatSection(array $data, string $section, StyleInterface $io): void
    {
        $args = [];
        foreach ($data as $domain) {
            if ($domain instanceof Release) {
                $key = sprintf(
                    '%s (%s) - %s',
                    $domain->getDate()->format('Y-m-d'),
                    $domain->getState(),
                    $domain->getVersion()
                );
            } elseif ($domain instanceof Function_ || $domain instanceof Constant_) {
                $key = $domain->getName();
                if (!empty($domain->getDeclaringClass())) {
                    $key = $domain->getDeclaringClass() . '::' . $key;
                }
            } else {
                $key = $domain->getName();
            }

            if (method_exists($domain, 'getPolyfill')) {
                $polyfills = $domain->getPolyfill() ?? '';
            } else {
                $polyfills = '';
            }

            if (method_exists($domain, 'getDeprecated')) {
                $deprecated = $domain->getDeprecated() ?? '';
            } else {
                $deprecated = '';
            }

            $flags = [];
            if ($domain instanceof Function_) {
                $parameters = $domain->getParameters();
                if ($domain->isAbstract()) {
                    $flags[] = 'A';
                }
                if ($domain->isFinal()) {
                    $flags[] = 'F';
                }
                if ($domain->isStatic()) {
                    $flags[] = 'S';
                }
            } else {
                $parameters = [];
            }

            $dependencies = [];

            foreach ($domain->getDependencies() as $dependency) {
                $name = $dependency->getName();
                $constraint = $dependency->getConstraint();
                $prettyConstraint = trim((string) (new VersionParser())->parseConstraints($constraint), '[]');
                $dependencies[] = sprintf('%s %s [%s]', $name, $constraint, $prettyConstraint);
            }

            $args[$key] = [
                $this->ext($domain) ? : $domain->getVersion(),
                $this->php($domain),
                $deprecated,
                implode(', ', $parameters),
                implode(', ', $flags),
                implode(', ', $dependencies),
                $polyfills,
            ];
        }
        ksort($args);

        $results = [];
        foreach ($args as $key => $values) {
            $parts = explode(' - ', $key);
            array_unshift($values, $parts[0]);
            $results[] = $values;
        }

        $io->section($section);

        $headers = ['', 'EXT min/Max', 'PHP min/Max', 'Deprecated', 'Parameters', 'Flags', 'Dependencies', 'Polyfills'];
        $footers = [
            sprintf('<info>Total [%d]</info>', count($results)),
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        $rows = $results;
        $rows[] = new TableSeparator();
        $rows[] = $footers;
        $io->table($headers, $rows);
    }

    private function ext(object $domain): string
    {
        return empty($domain->getExtMax())
            ? $domain->getExtMin()
            : $domain->getExtMin() . ' => ' . $domain->getExtMax()
        ;
    }

    private function php(object $domain): string
    {
        return empty($domain->getPhpMax())
            ? $domain->getPhpMin()
            : $domain->getPhpMin() . ' => ' . $domain->getPhpMax()
        ;
    }
}
