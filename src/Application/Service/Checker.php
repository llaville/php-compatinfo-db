<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Service;

use Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use function sprintf;
use const PHP_VERSION;

/**
 * Checks requirements for running CompatInfoDB.
 *
 * @since 3.0.0
 * @author Laurent Laville
 */
final class Checker
{
    private StyleInterface $io;
    private string $appName = 'Application';

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
