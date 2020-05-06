<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\ListCommand as AppListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * List all references supported by the Database.
 */
class ListCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('bartlett:db:list')
            ->setDescription('List all references supported by the Database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listCommand = new AppListCommand();

        list($rows, $refsCount, $loaded) = $this->commandBus->handle($listCommand);

        $footers = array(
            '<info>Total</info>',
            sprintf('<info>[%d]</info>', $refsCount),
            '',
            '',
            sprintf('<info>[%d]</info>', $loaded)
        );

        $rows[] = new TableSeparator();
        $rows[] = $footers;

        $headers = array('Reference', 'Version', 'State', 'Release Date', 'Loaded');

        // print results
        $this->printDbBuildVersion($output);
        $this->tableHelper($output, $headers, $rows);

        return 0;
    }
}
