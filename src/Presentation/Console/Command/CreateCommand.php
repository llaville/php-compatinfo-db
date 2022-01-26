<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Query\Init\InitQuery;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Create the database schema and load its contents from JSON files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        if (getenv('APP_ENV') === 'prod') {
            $io->caution('This operation should not be executed in a production environment!');
        }
        if (!$this->createDatabase($io)) {
            return self::FAILURE;
        }

        if (!$this->loadDatabase($io)) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function createDatabase(StyleInterface $io): bool
    {
        $metadatas = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (empty($metadatas)) {
            $io->error('No Metadata Classes found to create Database.');
            return false;
        }

        $schemaTool = new SchemaTool($this->entityManager);

        $io->writeln('Creating database schema...');

        try {
            $schemaTool->createSchema($metadatas);
        } catch (ToolsException $e) {
            $io->error($e->getMessage());
            return false;
        }

        $io->success('Database schema created successfully!');
        return true;
    }

    private function loadDatabase(StyleInterface $io): bool
    {
        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $appVersion = $app->getInstalledVersion(true);

        $io->writeln('Loading database contents...');

        $initQuery = new InitQuery($appVersion, $io, false, false);

        $exitCode = $this->queryBus->query($initQuery);

        $io->success('Database loaded successfully!');
        return ($exitCode === self::SUCCESS);
    }
}
