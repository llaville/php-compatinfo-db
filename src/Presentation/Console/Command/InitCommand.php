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

use function array_shift;
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

    /**
     * @inheritDoc
     */
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
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new Style($input, $output);

        if (getenv('APP_ENV') === 'prod') {
            $io->caution('This operation should not be executed in a production environment!');
        }

        $relVersion = $input->getArgument('rel_version') ?? null;

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        if (null === $relVersion) {
            $appVersion = $app->getLongVersion();
        } else {
            $appVersion = trim($relVersion);
        }
        $initCommand = new AppInitCommand(
            $appVersion,
            $io,
            $input->getOption('force'),
            $input->getOption('progress')
        );

        try {
            $this->commandBus->handle($initCommand);
        } catch (HandlerFailedException $e) {
            $failures = $e->getWrappedExceptions();
            $firstFailure = array_shift($failures);
            $io->error($firstFailure->getMessage());
            return self::FAILURE;
        }

        $io->success('Database built successfully!');
        return self::SUCCESS;
    }
}
