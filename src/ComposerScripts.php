<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb;

use Composer\Script\Event;

use function array_keys;
use function array_merge;
use function file_put_contents;
use function phpversion;
use function sprintf;
use function str_replace;
use function var_export;

/**
 * Composer script that prepare metadata cache file to compile PHAR version of application.
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class ComposerScripts
{
    private const METADATA_CACHE_FILE = '.box.metadata.cache';

    /**
     * Retrieve metadata cache file when box compile read metadata directive.
     *
     * @return array<int, string>
     */
    public static function getPharMetadata(): array
    {
        return require self::METADATA_CACHE_FILE;
    }

    /**
     * Prepare metadata cache file for box compile command.
     *
     * @param Event $event
     */
    public static function preparePharMetadata(Event $event): void
    {
        $composer = $event->getComposer();

        $locker = $composer->getLocker();
        $installed = [];

        foreach (array_keys($locker->getLockData()['platform']) as $type) {
            if ('php' === $type) {
                $installed[$type] = phpversion();
            } else {
                $installed[$type] = phpversion(str_replace('ext-', '', $type));
            }
        }

        foreach ($locker->getLockData()['packages'] as $package) {
            $installed[$package['name']] = $package['version'];
        }

        foreach ($locker->getLockData()['packages-dev'] as $package) {
            $installed[$package['name']] = $package['version'];
        }

        $rootPackage = $composer->getPackage();

        $componentCollection = [];

        $requires = array_merge(
            $rootPackage->getRequires(),
            $rootPackage->getDevRequires()
        );

        foreach ($requires as $type => $require) {
            $prettyString = (string) $require->getPrettyString($rootPackage);
            if (isset($installed[$type])) {
                $prettyString .= sprintf(' : <info>%s</info>', $installed[$type]);
            }
            $componentCollection[] = $prettyString;
        }

        file_put_contents(
            self::METADATA_CACHE_FILE,
            sprintf('<?php return %s;', var_export($componentCollection, true))
        );
    }
}
