<?php declare(strict_types=1);

/**
 * Event subscriber to inject profile time and memory usage at execution.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
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
 * @since Release 3.0.0
 */
final class ProfileEventSubscriber implements EventSubscriberInterface
{
    /** @var Stopwatch */
    private $stopwatch;

    /**
     * ProfileEventSubscriber constructor.
     *
     * @param Stopwatch $stopwatch
     */
    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritDoc}
     * @return array<string, string>
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->stopwatch->reset();
        // Just before executing any command
        $this->stopwatch->start($event->getCommand()->getName());
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
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

    /**
     * @param int $time
     * @return string
     */
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
