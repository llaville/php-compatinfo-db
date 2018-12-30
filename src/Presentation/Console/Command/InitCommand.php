<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\InitCommand as AppInitCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initiliaze the database with JSON files for one or all extensions.
 */
class InitCommand extends AbstractCommand
{
    protected function configure() : void
    {
        $this->setName('bartlett:db:init')
            ->setDescription('Load JSON file(s) in SQLite database')
            ->addArgument(
                'extension',
                InputArgument::OPTIONAL,
                'extension to load in database (case insensitive)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $extension = trim($input->getArgument('extension') ?? '');

        $initCommand = new AppInitCommand();
        $initCommand->extension = strtolower($extension);
        $initCommand->refDir = $this->getApplication()->getRefDir();
        $initCommand->dbFilename = $this->getApplication()->getDbFilename();
        $initCommand->appVersion = $this->getApplication()->getVersion();
        $initCommand->output = $output;

        $this->commandBus->handle($initCommand);
    }
}
