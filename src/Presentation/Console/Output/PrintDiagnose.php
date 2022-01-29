<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Output;

use Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use function getenv;
use function php_uname;
use function sprintf;
use const PHP_VERSION;

/**
 * Prints the `diagnose` command results.
 *
 * @author Laurent Laville
 * @since Release 3.19.0
 */
trait PrintDiagnose
{
    protected function write(RequirementsInterface $requirements, StyleInterface $io, string $appName): void
    {
        $io->title('Requirements Checker');

        $io->text(
            sprintf(
                '> Running %s with PHP <info>%s</info> on %s <info>%s</info>',
                $appName,
                PHP_VERSION,
                php_uname('s'),
                php_uname('r')
            )
        );
        $io->text('> PHP is using the following php.ini file:');
        if ($iniPath = $requirements->getPhpIniPath()) {
            $io->text(sprintf('<info>%s</info>', $iniPath));
        } else {
            $io->text('   WARNING: No configuration file (php.ini) used by PHP!', 'fg=yellow');
        }

        $io->text('');
        $io->text('> Checking ' . $appName . ' requirements:');

        $messages = ['ko' => [], 'error' => []];

        foreach ($requirements->getRequirements() as $requirement) {
            if ($requirement->isFulfilled()) {
                $messages['ok'][] = $requirement->getTestMessage();
                continue;
            }
            $messages['ko'][] = $requirement->getTestMessage();
            $messages['error'][] = $requirement->getHelpText();
        }
        $io->listing($messages['ok'], ['type' => '[x]', 'style' => 'fg=green']);
        $io->listing($messages['ko'], ['type' => '[ ]', 'style' => 'fg=red']);

        $env = [];
        $keys = ['APP_ENV', 'APP_DATABASE_URL', 'APP_PROXY_DIR', 'APP_VENDOR_DIR', 'APP_CACHE_DIR', 'APP_HOME_DIR'];
        foreach ($keys as $key) {
            $env[] = sprintf('[<comment>%s</comment>] %s', $key, getenv($key));
        }
        $io->text('? Environment');
        $io->listing($env, ['type' => ' > ', 'style' => 'fg=green']);

        if (empty($messages['error'])) {
            $io->success('Your system is ready to run the application.');
        } else {
            $io->error('Your system is not ready to run the application.');
            $io->section('Fix the following mandatory requirements:');
            $io->listing($messages['error'], ['style' => 'options=bold;fg=red']);
        }
    }
}
