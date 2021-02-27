<?php declare(strict_types=1);

/**
 * Update JSON files when a new PHP version is added.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseCommand as AppReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function trim;

/**
 * @since Release 2.0.0RC1
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

    protected function execute(InputInterface $input, OutputInterface $output)
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
