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
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

use function array_shift;

/**
 * Create the database schema and load its contents from JSON files
 *
 * @since Release 3.18.0
 * @author Laurent Laville
 */
class CreateCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:create';

    protected EntityManagerInterface $entityManager;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, EntityManagerInterface $em)
    {
        parent::__construct($commandBus, $queryBus);
        $this->entityManager = $em;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Create the database schema and load its contents from JSON files')
        ;
    }

    /**
     * {@inheritDoc}
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
            if ($firstFailure->getCode() < 500) {
                $io->warning($firstFailure->getMessage());
            } else {
                $io->error($firstFailure->getMessage());
            }
            return self::FAILURE;
        }

        $io->success('Database schema created successfully!');
        return self::SUCCESS;
    }
}
