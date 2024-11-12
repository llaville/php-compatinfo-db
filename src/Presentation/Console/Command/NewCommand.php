<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\Create\CreateCommand as AppCreateCommand;
use Bartlett\CompatInfoDb\Application\Command\Init\InitCommand as AppInitCommand;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

use function array_shift;

/**
 * Create the database schema and load its contents from JSON files.
 *
 * This command combines 'db:create' and 'db:init' actions.
 *
 * @since Release 6.1.0
 * @author Laurent Laville
 */
class NewCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:new';

    protected EntityManagerInterface $entityManager;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, EntityManagerInterface $em)
    {
        parent::__construct($commandBus, $queryBus);
        $this->entityManager = $em;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Create the database schema and load its contents from JSON files')
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

        $io->writeln('> Creating database schema...');

        $createCommand = new AppCreateCommand($this->entityManager);

        try {
            $this->commandBus->handle($createCommand);
        } catch (HandlerFailedException $e) {
            $failures = $e->getWrappedExceptions();
            $firstFailure = array_shift($failures);
            if ($firstFailure->getCode() == 500) {
                $io->error($firstFailure->getMessage());
                return self::FAILURE;
            }
        }

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $io->writeln('> Loading database ...');

        $appVersion = $app->getLongVersion();
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

        $autoGenerate = $app->getApplicationParameters()['compat_info_db.proxy_generate'];

        if ($autoGenerate === 'always') {
            $io->writeln('> Generating Proxies ...');

            $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
            $destPath = $this->entityManager->getConfiguration()->getProxyDir();

            $this->entityManager->getProxyFactory()->generateProxyClasses($metadata, $destPath);
        }

        $io->success('Database built successfully!');
        return self::SUCCESS;
    }
}
