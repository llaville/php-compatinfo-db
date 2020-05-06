<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\ReleaseCommand as AppReleaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Update JSON files when a new PHP version is released.
 */
class ReleaseCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('bartlett:db:release:php')
            ->setDescription('Fix php.max versions on new PHP release')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $releaseCommand = new AppReleaseCommand();
        $this->commandBus->handle($releaseCommand);

        return 0;
    }
}
