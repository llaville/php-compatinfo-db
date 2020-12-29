<?php declare(strict_types=1);

/**
 * Checks the minimum requirements on current platform.
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

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseQuery;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Service\Checker;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @since Release 2.0.0RC1
 */
class DiagnoseCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'diagnose';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface  $queryBus,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($commandBus, $queryBus);
        $this->entityManager = $entityManager;
    }

    protected function configure()
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

        return 0;
    }
}
