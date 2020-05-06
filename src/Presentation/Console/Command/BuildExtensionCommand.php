<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\BuildExtensionCommand as AppBuildExtensionCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build Extension draft JSON data
 */
class BuildExtensionCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('bartlett:db:build:ext')
            ->setDescription('Build Extension draft JSON data for SQLite compatinfo database')
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                'extension to extract components (case insensitive)'
            )
            ->addArgument(
                'ext_min',
                InputArgument::OPTIONAL,
                'extension MIN version',
                '0.1.0'
            )
            ->addArgument(
                'php_min',
                InputArgument::OPTIONAL,
                'php MIN version',
                '5.3.0'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buildExtensionCommand = new AppBuildExtensionCommand();
        $buildExtensionCommand->extension = trim($input->getArgument('extension'));
        $buildExtensionCommand->extMin = trim($input->getArgument('ext_min'));
        $buildExtensionCommand->phpMin = trim($input->getArgument('php_min'));
        $buildExtensionCommand->output = $output;

        $this->commandBus->handle($buildExtensionCommand);

        return 0;
    }
}
