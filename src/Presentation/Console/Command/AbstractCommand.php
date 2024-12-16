<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;

use Symfony\Component\Console\Command\Command;

/**
 * Base class for all application commands.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
abstract class AbstractCommand extends Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public function __construct(
        protected CommandBusInterface $commandBus,
        protected QueryBusInterface $queryBus
    ) {
        parent::__construct();
    }
}
