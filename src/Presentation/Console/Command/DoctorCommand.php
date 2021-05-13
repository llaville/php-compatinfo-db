<?php declare(strict_types=1);

/**
 * Checks the current installation of PHP CompatInfoDB.
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

use Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorQuery;
use Bartlett\CompatInfoDb\Application\Query\ListRef\ListQuery;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_values;
use function json_encode;
use function natsort;
use function sprintf;
use const JSON_PRETTY_PRINT;

/**
 * @since Release 3.6.0
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // retrieves only extensions installed in your platform
        $listQuery = new ListQuery(false, true, ApplicationInterface::VERSION);

        /** @var Platform $platform */
        $platform = $this->queryBus->query($listQuery);

        $doctorQuery = new DoctorQuery($platform, $input->getOption('with-tests'));
        $report = $this->queryBus->query($doctorQuery);

        if ($input->getOption('json')) {
            $output->writeln(json_encode($report, JSON_PRETTY_PRINT));
            return self::SUCCESS;
        }

        $io = new Style($input, $output);

        foreach ($report as $section => $info) {
            $io->section($section);

            foreach ($info as $key => $value) {
                if ('dependencies' === $key) {
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
                } else {
                    $io->columns(
                        $value,
                        sprintf('  %-20s', $key) . ': %s'
                    );
                }
            }
        }

        return self::SUCCESS;
    }
}
