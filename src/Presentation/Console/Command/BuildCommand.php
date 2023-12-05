<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Build\BuildCommand as AppBuildCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build new JSON definition files for an extension already loaded in memory.
 *
 * @since Release 3.5.0
 * @author Laurent Laville
 */
final class BuildCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:build';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Build JSON definition file(s) for an extension')
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

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $buildCommand = new AppBuildCommand(
            trim($input->getArgument('extension')),
            trim($input->getArgument('ext_min')),
            trim($input->getArgument('php_min')),
            $output
        );

        $this->commandBus->handle($buildCommand);

        $io = new Style($input, $output);
        $io->writeln('');
        $io->success('New JSON files was produced for this extension');

        return self::SUCCESS;
    }
}
