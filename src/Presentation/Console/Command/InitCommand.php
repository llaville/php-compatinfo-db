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
    private const DEFAULT_REL_VERSION = '2.x-dev';

    protected function configure()
    {
        $this->setName('bartlett:db:init')
            ->setDescription('Load JSON file(s) in SQLite database')
            ->addArgument(
                'rel_version',
                InputArgument::OPTIONAL,
                'New DB version',
                self::DEFAULT_REL_VERSION
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $initCommand = new AppInitCommand();
        $initCommand->extension = '';
        $initCommand->refDir = $this->getApplication()->getRefDir();
        $initCommand->dbFilename = $this->getApplication()->getDbFilename();

        $relVersion = trim($input->getArgument('rel_version'));
        if (self::DEFAULT_REL_VERSION == $relVersion) {
            $initCommand->appVersion = $this->getApplication()->getVersion();
        } else {
            $initCommand->appVersion = $relVersion;
        }
        $initCommand->output = $output;

        $this->commandBus->handle($initCommand);

        return 0;
    }
}
