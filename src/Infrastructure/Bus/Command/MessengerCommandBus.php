<?php declare(strict_types=1);

/**
 * Messenger Command Bus implementation.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @since Release 3.0.0
 */
final class MessengerCommandBus implements CommandBusInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /**
     * MessengerCommandBus constructor.
     *
     * @param MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(CommandInterface $command)
    {
        $this->messageBus->dispatch($command);
    }
}
