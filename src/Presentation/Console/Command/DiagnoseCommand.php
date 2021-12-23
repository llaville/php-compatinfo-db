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
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Diagnoses the system to identify common errors')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diagnoseQuery = new DiagnoseQuery($this->entityManager->getConnection());

        $projectRequirements = $this->queryBus->query($diagnoseQuery);

        $io = new Style($input, $output);

        $checker = new Checker($io);
        $checker->setAppName('PHP CompatInfoDB');
        $checker->printDiagnostic($projectRequirements);

        return self::SUCCESS;
    }
}
