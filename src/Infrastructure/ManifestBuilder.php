<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure;

use KevinGH\Box\Composer\Manifest\ManifestBuilderInterface;

use function implode;
use function sprintf;
use function substr;
use const PHP_EOL;

final class ManifestBuilder implements ManifestBuilderInterface
{
    /**
     * @param array<string, mixed> $content
     */
    public function __invoke(array $content): string
    {
        $composerJson = $content['composer.json'];
        $installedPhp = $content['installed.php'];
        $rootPackage = $installedPhp['root'];
        $entries = [];

        if (isset($rootPackage['pretty_version'])) {
            $version = sprintf(
                '%s@%s',
                $rootPackage['pretty_version'],
                substr($rootPackage['reference'], 0, 7)
            );
        } else {
            $version = $rootPackage['version'];
        }
        $entries[] = sprintf('%s: <info>%s</info>', $rootPackage['name'], $version);

        $prefix = "bartlett/php-compatinfo-db requires";

        foreach ($composerJson['require'] as $req => $constraint) {
            if ('php' === $req) {
                $entries[] = sprintf('%s %s: <info>%s</info>', $prefix, "$req $constraint", \phpversion());
            } elseif (substr($req, 0, 4) === 'ext-') {
                $extension = substr($req, 4);
                $entries[] = sprintf('%s %s: <info>%s</info>', $prefix, "$req $constraint", \phpversion($extension));
            } else {
                $installedPhp['versions'][$req]['constraint'] = $constraint;
            }
        }

        foreach ($installedPhp['versions'] as $package => $values) {
            if ($rootPackage['name'] === $package) {
                continue;
            }
            if (isset($values['pretty_version'])) {
                $constraint = $values['constraint'] ?? '';
                $entries[] = sprintf('%s %s: <info>%s</info>', $prefix, "$package $constraint", $values['pretty_version']);
            } // otherwise, it's a virtual package
        }

        return implode(PHP_EOL, $entries);
    }
}
