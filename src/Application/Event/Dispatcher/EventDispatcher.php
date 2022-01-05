<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Event\Dispatcher;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event dispatcher that will inject profile time and memory usage at execution.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class EventDispatcher extends SymfonyEventDispatcher
{
    public function __construct(
        InputInterface $input,
        EventSubscriberInterface $profileEventSubscriber
    ) {
        parent::__construct();

        if ($input->hasParameterOption('--profile')) {
            $this->addSubscriber($profileEventSubscriber);
        }
    }
}
