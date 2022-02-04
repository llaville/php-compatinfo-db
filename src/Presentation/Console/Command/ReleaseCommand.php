<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseCommand as AppReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function trim;

/**
 * Update JSON files when a new PHP version is added.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class ReleaseCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:release';

    protected function configure(): void
    {
        $this->setName(self::NAME)
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $releaseCommand = new AppReleaseCommand(
            trim($input->getArgument('rel_version')),
            trim($input->getArgument('rel_date')),
            trim($input->getArgument('rel_state'))
        );

        $this->commandBus->handle($releaseCommand);

        $io = new Style($input, $output);
        $io->success('New release was added in JSON files');
        $io->note('Do not forget to update constants of ExtensionVersionProviderInterface');

        return self::SUCCESS;
    }
}
