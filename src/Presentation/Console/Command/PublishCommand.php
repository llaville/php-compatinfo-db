<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\PublishCommand as AppPublishCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends AbstractCommand
{
    protected function configure() : void
    {
        $this->setName('bartlett:db:publish:php')
            ->setDescription('Add new PHP release')
            ->addArgument(
                'rel_version',
                InputArgument::REQUIRED,
                'New PHP release version'
            )
            ->addArgument(
                'rel_date',
                InputArgument::OPTIONAL,
                'New PHP release date',
                date('Y-m-d')
            )
            ->addArgument(
                'rel_state',
                InputArgument::OPTIONAL,
                'New PHP release state (alpha, beta, RC, stable)',
                'stable'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $publishCommand = new AppPublishCommand();
        $publishCommand->relVersion = trim($input->getArgument('rel_version'));
        $publishCommand->relDate = trim($input->getArgument('rel_date'));
        $publishCommand->relState = trim($input->getArgument('rel_state'));

        $this->commandBus->handle($publishCommand);
    }

}