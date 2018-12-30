<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class AbstractCommand extends Command
{
    /** @var CommandBus */
    protected $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * Prints the database current build version
     *
     * @param OutputInterface $output Console Output concrete instance
     *
     * @return void
     */
    protected function printDbBuildVersion(OutputInterface $output) : void
    {
        $output->writeln(
            sprintf(
                '<info>Reference Database Version</info> => %s%s',
                $this->getApplication()->getDbVersions()['build.version'],
                PHP_EOL

            )
        );
    }

    /**
     * Helper that convert analyser results to a console table
     *
     * @param OutputInterface $output  Console Output concrete instance
     * @param array           $headers All table headers
     * @param array           $rows    All table rows
     * @param string          $style   The default style name to render tables
     *
     * @return void
     */
    protected function tableHelper(OutputInterface $output, array $headers, array $rows, string $style = 'compact') : void
    {
        $table = new Table($output);
        $table->setStyle($style)
            ->setHeaders($headers)
            ->setRows($rows)
            ->render()
        ;
    }

    /**
     * Helper that convert an array key-value pairs to a console report.
     *
     * See Structure and Loc analysers for implementation examples
     *
     * @param OutputInterface $output Console Output concrete instance
     * @param array           $lines  Any analyser formatted metrics
     *
     * @return void
     */
    protected function printFormattedLines(OutputInterface $output, array $lines) : void
    {
        foreach ($lines as $ident => $contents) {
            list ($format, $args) = $contents;
            $output->writeln(vsprintf($format, $args));
        }
    }
}