<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Event\Subscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Stopwatch\Stopwatch;

use function floor;
use function sprintf;
use const PHP_EOL;

/**
 * Event subscriber to inject profile time and memory usage at execution.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class ProfileEventSubscriber implements EventSubscriberInterface
{
    /**
     * ProfileEventSubscriber constructor.
     */
    public function __construct(
        private readonly Stopwatch $stopwatch
    ) {
    }

    /**
     * @inheritDoc
     * @return array<string, string>
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
        ];
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->stopwatch->reset();
        // Just before executing any command
        $this->stopwatch->start($event->getCommand()->getName());
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        // Just after executing any command
        $stopwatchEvent = $this->stopwatch->stop($event->getCommand()->getName());

        $input = $event->getInput();

        if (false === $input->hasParameterOption('--profile')) {
            return;
        }

        $output = $event->getOutput();

        $time   = $stopwatchEvent->getDuration();
        $memory = $stopwatchEvent->getMemory();

        $text = sprintf(
            '%s<comment>Time: %s, Memory: %4.2fMb</comment>',
            PHP_EOL,
            $this->toTimeString($time),
            sprintf('%4.2fMb', $memory / (1024 * 1024))
        );
        $output->writeln($text);
    }

    private function toTimeString(int $time): string
    {
        $times = [
            'hour'   => 3600000,
            'minute' => 60000,
            'second' => 1000
        ];

        $ms = $time;

        foreach ($times as $unit => $value) {
            if ($ms >= $value) {
                $time = floor($ms / $value * 100.0) / 100.0;
                return $time . ' ' . ($time == 1 ? $unit : $unit . 's');
            }
        }
        return $ms . ' ms';
    }
}
