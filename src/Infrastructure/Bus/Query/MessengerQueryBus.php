<?php declare(strict_types=1);

/**
 * Messenger Query Bus implementation.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Query;

use Bartlett\CompatInfoDb\Application\Query\QueryBusInterface;
use Bartlett\CompatInfoDb\Application\Query\QueryInterface;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @since Release 3.0.0
 */
final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    /**
     * MessengerQueryBus constructor.
     *
     * @param MessageBusInterface $queryBus
     */
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * {@inheritDoc}
     */
    public function query(QueryInterface $query)
    {
        return $this->handle($query);
    }
}
