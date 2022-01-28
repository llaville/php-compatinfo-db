<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseQuery;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Service\Checker;
use Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function count;

/**
 * Checks the minimum requirements on current platform.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class DiagnoseCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'diagnose';

    private EntityManagerInterface $entityManager;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($commandBus, $queryBus);
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Diagnoses the system to identify common errors')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $diagnoseQuery = new DiagnoseQuery($this->entityManager->getConnection());

        /** @var ProjectRequirements $projectRequirements */
        $projectRequirements = $this->queryBus->query($diagnoseQuery);

        $io = new Style($input, $output);

        $checker = new Checker($io);
        $checker->setAppName('PHP CompatInfoDB');
        $checker->printDiagnostic($projectRequirements);

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();
        $io->note(
            sprintf(
                '%s version %s',
                $app->getName(),
                $app->getInstalledVersion()
            )
        );

        if (count($projectRequirements->getFailedRequirements()) === 0) {
            return self::SUCCESS;
        }
        return self::FAILURE;
    }
}
