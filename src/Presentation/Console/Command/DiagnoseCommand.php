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
use Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Output\PrintDiagnose;
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
    use PrintDiagnose;

    public const NAME = 'diagnose';

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct($commandBus, $queryBus);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Diagnoses the system to identify common errors')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $diagnoseQuery = new DiagnoseQuery($this->entityManager->getConnection());

        /** @var ProjectRequirements $projectRequirements */
        $projectRequirements = $this->queryBus->query($diagnoseQuery);

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();  // @phpstan-ignore varTag.nativeType

        $io = new Style($input, $output);

        $this->write($projectRequirements, $io, 'PHP CompatInfoDB', $app->getApplicationParameters());
        $io->note(
            sprintf(
                '%s version %s',
                $app->getName(),
                $app->getLongVersion()
            )
        );

        if (count($projectRequirements->getFailedRequirements()) === 0) {
            return self::SUCCESS;
        }
        return self::FAILURE;
    }
}
