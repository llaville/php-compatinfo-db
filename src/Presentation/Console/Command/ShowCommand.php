<?php declare(strict_types=1);

/**
 * Show details of a reference (extension) supported.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\Show\ShowQuery;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_;
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
use function sprintf;
use function trim;

/**
 * @since Release 2.0.0RC1
 */
final class ShowCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:show';

    use LibraryVersionProviderTrait;

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
            $input->getOption('dependencies')
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

        if (!$summary) {
            return self::SUCCESS;
        }

        $io->title('Reference Summary');
        $io->columns(
            count($extension->getReleases()),
            '  Releases                                  %10d'
        );
        $io->columns(
            count($extension->getIniEntries()),
            '  INI entries                               %10d'
        );
        $io->columns(
            count($extension->getConstants()),
            '  Constants                                 %10d'
        );
        $io->columns(
            count($extension->getFunctions()),
            '  Functions                                 %10d'
        );
        $io->columns(
            count($extension->getClasses()),
            '  Classes                                   %10d'
        );
        $io->columns(
            count($extension->getInterfaces()),
            '  Interfaces                                %10d'
        );
        $io->columns(
            count($extension->getClassConstants()),
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

        return self::SUCCESS;
    }

    /**
     * @param Dependency[] $data
     * @param StyleInterface $io
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
            $rows[$constraint] = [$name, $verified ? $constraint : '<error>'.$constraint.'</error>', $verified ? 'Y' : 'N'];
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
     * @param string $section
     * @param StyleInterface $io
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
                $prettyConstraint = trim((string) (new VersionParser)->parseConstraints($constraint), '[]');
                $dependencies[] = sprintf('%s %s [%s]', $name, $constraint, $prettyConstraint);
            }

            $args[$key] = [
                $this->ext($domain) ? : $domain->getVersion(),
                $this->php($domain),
                '',
                implode(', ', $parameters),
                implode(', ', $flags),
                implode(', ', $dependencies),
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

        $headers = ['', 'EXT min/Max', 'PHP min/Max', 'Deprecated', 'Parameters', 'Flags', 'Dependencies'];
        $footers = [
            sprintf('<info>Total [%d]</info>', count($results)),
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

    /**
     * @param object $domain
     * @return string
     */
    private function ext($domain): string
    {
        return empty($domain->getExtMax())
            ? $domain->getExtMin()
            : $domain->getExtMin() . ' => ' . $domain->getExtMax()
        ;
    }

    /**
     * @param object $domain
     * @return string
     */
    private function php($domain): string
    {
        return empty($domain->getPhpMax())
            ? $domain->getPhpMin()
            : $domain->getPhpMin() . ' => ' . $domain->getPhpMax()
        ;
    }
}
