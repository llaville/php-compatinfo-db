<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand as AppDiagnoseCommand;
use Bartlett\CompatInfoDb\DatabaseFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Laminas\Diagnostics\Result\FailureInterface;
use Laminas\Diagnostics\Result\SuccessInterface;

/**
 * Checks the minimum requirements on current platform for the phar distribution
 */
class DiagnoseCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('bartlett:diagnose')
            ->setDescription('Diagnoses the system to identify common errors')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diagnoseCommand = new AppDiagnoseCommand();
        $diagnoseCommand->databaseParams = DatabaseFactory::getDsn('sqlite');

        $results = $this->commandBus->handle($diagnoseCommand);

        foreach ($results as $check) {
            if ($results[$check] instanceof FailureInterface) {
                $output->writeln('- <error>KO</error> - ' . $results[$check]->getMessage());
            } elseif ($results[$check] instanceof SuccessInterface) {
                $output->writeln('- <info>OK</info> - ' . $results[$check]->getMessage());
            }
        }
    }
}
