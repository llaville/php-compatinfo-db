<?php declare(strict_types=1);

/**
 * Base class for all application commands.
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
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;

use Symfony\Component\Console\Command\Command;

/**
 * @since Release 2.0.0RC1
 */
abstract class AbstractCommand extends Command
{
    /** @var CommandBusInterface */
    protected $commandBus;

    /** @var QueryBusInterface */
    protected $queryBus;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }
}
