<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Query;

use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Messenger Query Bus implementation.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    /**
     * MessengerQueryBus constructor.
     *
     */
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
