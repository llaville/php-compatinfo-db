<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;
use Bartlett\BoxManifest\Composer\ManifestFactory;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;

use function implode;
use function sprintf;
use function substr;
use const PHP_EOL;

final class ManifestBuilder implements ManifestBuilderInterface
{
    public static function toText(Configuration $config, Box $box): ?string
    {
        return ManifestFactory::create(self::class, $config, $box);
    }

    /**
     * {@inheritDoc}
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

        $allRequirements = [
            '' => $composerJson['require'],
            ' (for development)' => $composerJson['require-dev'],
        ];

        foreach ($allRequirements as $category => $requirements) {
            foreach ($requirements as $req => $constraint) {
                if (!empty($constraint)) {
                    $constraint = sprintf('<comment>%s</comment>', $constraint);
                }
                if ('php' === $req) {
                    $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$req $constraint", \phpversion());
                } elseif (substr($req, 0, 4) === 'ext-') {
                    $extension = substr($req, 4);
                    $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$req $constraint", \phpversion($extension));
                } else {
                    $installedPhp['versions'][$req]['constraint'] = $constraint;
                    $installedPhp['versions'][$req]['category'] = $category;
                }
            }
        }

        foreach ($installedPhp['versions'] as $package => $values) {
            if ($rootPackage['name'] === $package) {
                continue;
            }
            if (isset($values['pretty_version'])) {
                $category = $values['category'] ?? '';
                $constraint = $values['constraint'] ?? '';
                $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$package $constraint", $values['pretty_version']);
            } // otherwise, it's a virtual package
        }

        return implode(PHP_EOL, $entries);
    }
}
