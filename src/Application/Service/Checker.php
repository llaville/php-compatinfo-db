<?php declare(strict_types=1);

/**
 * Checks requirements for running CompatInfoDB.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Service;

use Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use function sprintf;
use const PHP_VERSION;

/**
 * @since 3.0.0
 */
final class Checker
{
    /** @var StyleInterface  */
    private $io;

    /** @var string  */
    private $appName = 'Application';

    /**
     * Checker constructor.
     *
     * @param StyleInterface $io
     */
    public function __construct(StyleInterface $io)
    {
        $this->io = $io;
    }

    /**
     * @param string $name
     */
    public function setAppName(string $name): void
    {
        $this->appName = $name;
    }

    /**
     * @param RequirementsInterface $requirements
     */
    public function printDiagnostic(RequirementsInterface $requirements): void
    {
        $this->io->title($this->appName . ' Requirements Checker');

        $this->io->text(sprintf('> Using PHP <info>%s</info>', PHP_VERSION));
        $this->io->text('> PHP is using the following php.ini file:');
        if ($iniPath = $requirements->getPhpIniPath()) {
            $this->io->text(sprintf('<info>%s</info>', $iniPath));
        } else {
            $this->io->text('   WARNING: No configuration file (php.ini) used by PHP!', 'fg=yellow');
        }

        $this->io->text('');
        $this->io->text('> Checking ' . $this->appName . ' requirements:');

        $messages = ['ko' => [], 'error' => []];

        foreach ($requirements->getRequirements() as $requirement) {
            if ($requirement->isFulfilled()) {
                $messages['ok'][] = $requirement->getTestMessage();
                continue;
            }
            $messages['ko'][] = $requirement->getTestMessage();
            $messages['error'][] = $requirement->getHelpText();
        }
        $this->io->listing($messages['ok'], ['type' => '[x]', 'style' => 'fg=green']);
        $this->io->listing($messages['ko'], ['type' => '[ ]', 'style' => 'fg=red']);

        if (empty($messages['error'])) {
            $this->io->success('Your system is ready to run the application.');
        } else {
            $this->io->error('Your system is not ready to run the application.');
            $this->io->section('Fix the following mandatory requirements:');
            $this->io->listing($messages['error'], ['style' => 'options=bold;fg=red']);
        }
    }
}
