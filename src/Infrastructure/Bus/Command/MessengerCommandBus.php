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
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function handle(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
