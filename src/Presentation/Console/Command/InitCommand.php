<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Init\InitCommand as AppInitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

use function getenv;
use function trim;

/**
 * Initialize the database with JSON files for all extensions.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class InitCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:init';

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Load JSON file(s) into database')
            ->addArgument(
                'rel_version',
                InputArgument::OPTIONAL,
                'New DB version'
            )
            ->addOption('force', 'f', null, 'Reset database contents even if not empty')
            ->addOption('progress', null, null, 'Show progress bar')
            ->addOption('installed', 'i', null, 'Initialize current PHP version platform only')
            ->addOption('distribution', 'd', null, 'Initialize distribution platform only')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new Style($input, $output);

        if (!$input->getOption('installed') && !$input->getOption('distribution')) {
            $io->error('You should specify either installed (-i) or distribution (-d) option. None are given.');
            return self::FAILURE;
        }

        if (getenv('APP_ENV') === 'prod') {
            $io->caution('This operation should not be executed in a production environment!');
        }

        $relVersion = $input->getArgument('rel_version') ?? null;

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        if (null === $relVersion) {
            $appVersion = $app->getInstalledVersion(true);
        } else {
            $appVersion = trim($relVersion);
        }
        $initCommand = new AppInitCommand(
            $appVersion,
            $io,
            $input->getOption('force'),
            $input->getOption('progress'),
            $input->getOption('installed'),
            $input->getOption('distribution'),
        );

        try {
            $this->commandBus->handle($initCommand);
        } catch (HandlerFailedException $e) {
            $firstFailure = $e->getNestedExceptions()[0];
            $io->error($firstFailure->getMessage());
            return self::FAILURE;
        }

        $io->success('Database built successfully!');
        return self::SUCCESS;
    }
}
