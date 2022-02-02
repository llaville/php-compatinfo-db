<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorQuery;
use Bartlett\CompatInfoDb\Application\Query\ListRef\ListQuery;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_values;
use function count;
use function json_encode;
use function natsort;
use function sprintf;
use function strpos;
use function substr;
use function trim;
use const JSON_PRETTY_PRINT;

/**
 * Checks the current installation of PHP CompatInfoDB.
 *
 * @since Release 3.6.0
 * @author Laurent Laville
 */
final class DoctorCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'doctor';

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Checks the current installation')
            ->addOption('json', null, null, 'Report checks execution results in JSON format')
            ->addOption('with-tests', null, null, 'Include Unit tests in results')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ApplicationInterface $app */
        $app = $this->getApplication();
        $installedVersion = $app->getInstalledVersion(true);

        // retrieves only extensions installed in your platform
        $listQuery = new ListQuery($installedVersion);

        /** @var Platform $platform */
        $platform = $this->queryBus->query($listQuery);

        $withTests = $input->getOption('with-tests');

        $doctorQuery = new DoctorQuery($platform, $withTests, $installedVersion);
        $report = $this->queryBus->query($doctorQuery);
        $status = $report['status'];

        if ($input->getOption('json')) {
            $output->writeln(json_encode($report, JSON_PRETTY_PRINT));
            return $status < 2 ? self::SUCCESS : self::FAILURE;
        }
        unset($report['status']);

        $io = new Style($input, $output);

        $statusNote = [];

        foreach ($report as $section => $info) {
            $io->section($section);

            foreach ($info as $key => $value) {
                if ('dependencies' === $key) {
                    if ('PHP' === $section) {
                        $io->text(sprintf('  %-20s:', $key));
                        foreach ($value as $library => $result) {
                            $io->columns($result, sprintf('    - %-16s', $library) . ': %s');
                        }
                        continue;
                    }
                    if (count($value) === 0) {
                        $io->text(sprintf('  %-20s: none', $key));
                    } else {
                        $io->text(sprintf('  %-20s:', $key));
                        $indent = '    ';
                        foreach ($value as $dep => $constraints) {
                            natsort($constraints['passed']);
                            natsort($constraints['skipped']);
                            $io->text($indent . '- ' . $dep . ' ' . $constraints['version']);
                            $io->listing(array_values($constraints['passed']), ['indent' => $indent . '  ', 'type' => '[x]', 'style' => 'fg=green']);
                            $io->listing(array_values($constraints['skipped']), ['indent' => $indent . '  ', 'type' => '[ ]', 'style' => 'fg=red']);

                            if (count($constraints['skipped']) > 0) {
                                $statusNote[$section] = sprintf('<info>%s</info> -- %d constraints skipped', $dep, count($constraints['skipped']));
                            }
                        }
                    }
                } elseif ('constraints' === $key) {
                    $io->text(sprintf('  %-20s:', $key));
                    foreach ($value as $constraint => $result) {
                        $io->columns($result, sprintf('    - %-16s', $constraint) . ': %s');
                    }
                } elseif ('tests' === $key) {
                    $io->text(sprintf('  %-20s:', $key));
                    $io->note($value);
                    list($result) = array_values($value);
                    if ($offset = strpos($result, 'Tests:')) {
                        $statusNote[$section] = sprintf('<info>%s</info> -- %s', $section, trim(substr($result, $offset)));
                    }
                } else {
                    $io->columns(
                        $value,
                        sprintf('  %-20s', $key) . ': %s'
                    );
                }
            }
        }

        if ($status === 0) {
            if ($withTests) {
                $io->success('All tests and dependency checks are passed.');
            } else {
                $io->success('All dependency checks are passed.');
            }
        } elseif ($status === 3) {
            $io->warning('Some tests and dependency checks are failed.');
        } elseif ($status === 2) {
            $io->warning('Some tests are failed.');
        } else {
            $io->warning('Some dependency checks are failed.');
        }
        natsort($statusNote);
        $io->comment($statusNote);

        return $status < 2 ? self::SUCCESS : self::FAILURE;
    }
}
