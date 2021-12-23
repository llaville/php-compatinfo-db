<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Messenger Command Bus implementation.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class MessengerCommandBus implements CommandBusInterface
{
    private MessageBusInterface $messageBus;

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
