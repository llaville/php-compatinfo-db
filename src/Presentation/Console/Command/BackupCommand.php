<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\BackupCommand as AppBackupCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Backup copy of the database
 */
class BackupCommand extends AbstractCommand
{
    protected function configure() : void
    {
        $this->setName('bartlett:db:backup')
            ->setDescription('Backup the current SQLite compatinfo database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $backupCommand = new AppBackupCommand();
        $backupCommand->source = $this->getApplication()->getDbFilename();
        $backupCommand->target = null;

        $copied = $this->commandBus->handle($backupCommand);

        if ($copied) {
            $message = sprintf(
                'Database <info>%s</info>' .
                ' was copied to <comment>%s</comment>',
                $backupCommand->source,
                $backupCommand->target
            );
        } else {
            $message = sprintf(
                'Unable to copy Database <info>%s</info>' .
                ' to <comment>%s</comment>',
                $backupCommand->source,
                $backupCommand->target
            );
        }
        $output->writeln($message);
    }
}
