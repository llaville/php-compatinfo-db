<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Event\Dispatcher;

use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\AbstractCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use function str_starts_with;

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

        $this->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) {
            $command = $event->getCommand();

            if (str_starts_with($command->getName(), 'db:') && $command->getName() !== 'db:create') {
                /** @var ApplicationInterface $app */
                $app = $command->getApplication();
                // launch auto diagnostic
                $diagnoseCommand = $app->find('diagnose');
                // and avoid to print results
                $statusCode = $diagnoseCommand->run($event->getInput(), new NullOutput());
                if ($statusCode === AbstractCommand::FAILURE) {
                    $event->disableCommand();
                }
            }
        }, 100); // with a priority highest to default (in case of --profile usage)

        $this->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) {
            $command = $event->getCommand();
            if (str_starts_with($command->getName(), 'db:') && $event->getExitCode() == ConsoleCommandEvent::RETURN_CODE_DISABLED) {
                $io = new Style($event->getInput(), $event->getOutput());
                $io->error('Please run `db:create` to initialize the database.');
            }
        }, 100); // with a priority highest to default (in case of --profile usage)
    }
}
