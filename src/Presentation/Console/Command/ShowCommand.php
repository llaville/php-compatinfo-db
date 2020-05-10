<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\ShowCommand as AppShowCommand;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends AbstractCommand
{
    public const NAME = 'bartlett:db:show';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Show details of a reference supported in the Database.')
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $showCommand = new AppShowCommand(
            trim($input->getArgument('extension')),
            $input->getOption('releases'),
            $input->getOption('ini'),
            $input->getOption('constants'),
            $input->getOption('functions'),
            $input->getOption('interfaces'),
            $input->getOption('classes'),
            $input->getOption('methods'),
            $input->getOption('class-constants')
        );

        $results = $this->commandBus->handle($showCommand);

        // print results
        $this->printDbBuildVersion($output);

        if (array_key_exists('summary', $results)) {
            $summary = $results['summary'];
            $output->writeln(sprintf('%s<info>Reference Summary</info>', PHP_EOL));
            $summary['releases'] = array(
                '  Releases                                  %10d',
                array($summary['releases'])
            );
            $summary['iniEntries'] = array(
                '  INI entries                               %10d',
                array($summary['iniEntries'])
            );
            $summary['constants'] = array(
                '  Constants                                 %10d',
                array($summary['constants'])
            );
            $summary['functions'] = array(
                '  Functions                                 %10d',
                array($summary['functions'])
            );
            $summary['interfaces'] = array(
                '  Interfaces                                %10d',
                array($summary['interfaces'])
            );
            $summary['classes'] = array(
                '  Classes                                   %10d',
                array($summary['classes'])
            );
            $summary['class-constants'] = array(
                '  Class Constants                           %10d',
                array($summary['class-constants'])
            );
            $summary['methods'] = array(
                '  Methods                                   %10d',
                array($summary['methods'])
            );
            $summary['static methods'] = array(
                '  Static Methods                            %10d',
                array($summary['static methods'])
            );
            $this->printFormattedLines($output, $summary);
            return 0;
        }

        foreach ($results as $title => $values) {
            $args = array();

            foreach ($values as $key => $val) {
                if (strcasecmp($title, 'releases') == 0) {
                    $key = sprintf('%s (%s)', $val['date'], $val['state']);

                } elseif (strcasecmp($title, 'methods') == 0
                    || strcasecmp($title, 'static methods') == 0
                    || strcasecmp($title, 'class-constants') == 0
                ) {
                    foreach ($val as $meth => $v) {
                        $k = sprintf('%s::%s', $key, $meth);
                        $args[$k] = $v;
                    }
                    continue;
                }
                $args[$key] = $val;
            }

            $rows = array();
            ksort($args);

            foreach ($args as $arg => $versions) {
                $row = array(
                    $arg,
                    self::ext($versions),
                    self::php($versions),
                    self::deprecated($versions),
                );
                $rows[] = $row;
            }

            $headers = array(ucfirst($title), 'EXT min/Max', 'PHP min/Max', 'Deprecated');
            $footers = array(
                sprintf('<info>Total [%d]</info>', count($args)),
                '',
                '',
                ''
            );
            $rows[] = new TableSeparator();
            $rows[] = $footers;

            $this->tableHelper($output, $headers, $rows);
            $output->writeln('');
        }

        return 0;
    }

    private static function ext(array $versions) : string
    {
        return empty($versions['ext.max'])
            ? $versions['ext.min']
            : $versions['ext.min'] . ' => ' . $versions['ext.max'];
    }

    private static function php(array $versions) : string
    {
        return empty($versions['php.max'])
            ? $versions['php.min']
            : $versions['php.min'] . ' => ' . $versions['php.max'];
    }

    private static function deprecated(array $versions) : string
    {
        if (isset($versions['deprecated'])) {
            return $versions['deprecated'];
        }
        return '';
    }
}
