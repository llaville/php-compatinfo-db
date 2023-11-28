<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\ListRef;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

use RuntimeException;
use function phpversion;
use function preg_match;
use function str_replace;
use function strcasecmp;

/**
 * Handler to list references in the database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class ListHandler implements QueryHandlerInterface, ExtensionVersionProviderInterface
{
    use ExtensionVersionProviderTrait;

    private DistributionRepository $distributionRepository;

    /**
     * ListHandler constructor.
     */
    public function __construct(
        DistributionRepository $distributionRepository
    ) {
        $this->distributionRepository = $distributionRepository;
    }

    public function __invoke(ListQuery $query): Platform
    {
        $platform = $this->distributionRepository->getDistributionByVersion($query->getAppVersion());
        if (null === $platform) {
            throw new RuntimeException(
                "Distribution platform is not available. Please run `diagnose` command to learn more."
            );
        }

        $filters = $query->getFilters();

        if (isset($filters['type'])) {
            $platform = $this->filterPlatformByExtensionType($platform, $filters['type']);
        }
        if (isset($filters['name'])) {
            $platform = $this->filterPlatformByExtensionName($platform, $filters['name']);
        }
        if (!empty($filters['installed'])) {
            $platform = $this->filterPlatformByExtensionInstalled($platform);
        }
        if (!empty($filters['outdated'])) {
            $platform = $this->filterPlatformByExtensionOutdated($platform);
        }

        return $platform;
    }

    private function filterPlatformByExtensionType(Platform $platform, string $type): Platform
    {
        $extensions = [];
        foreach ($platform->getExtensions() as $extension) {
            if (0 === strcasecmp($extension->getType(), $type)) {
                $extensions[] = $extension;
            }
        }

        return new Platform(
            $platform->getDescription(),
            $platform->getVersion(),
            $platform->getCreatedAt(),
            $extensions
        );
    }

    private function filterPlatformByExtensionName(Platform $platform, string $name): Platform
    {
        $name = str_replace('*', '.*', $name);
        $extensions = [];
        foreach ($platform->getExtensions() as $extension) {
            if (preg_match('/^(' . $name . ')$/', $extension->getName())) {
                $extensions[] = $extension;
            }
        }

        return new Platform(
            $platform->getDescription(),
            $platform->getVersion(),
            $platform->getCreatedAt(),
            $extensions
        );
    }

    private function filterPlatformByExtensionInstalled(Platform $platform): Platform
    {
        $extensions = [];

        foreach ($platform->getExtensions() as $extension) {
            $name = $extension->getName();
            if (strcasecmp('opcache', $name) === 0) {
                // special case
                $name = 'Zend ' . $name;
            }
            $installed = phpversion($name) ? : '';
            $provided = $extension->getVersion();

            if ($installed !== '' && $installed == $provided) {
                $extensions[] = $extension;
            }
        }

        return new Platform(
            $platform->getDescription(),
            $platform->getVersion(),
            $platform->getCreatedAt(),
            $extensions
        );
    }

    private function filterPlatformByExtensionOutdated(Platform $platform): Platform
    {
        $extensions = [];

        foreach ($platform->getExtensions() as $extension) {
            $name = $extension->getName();
            if (strcasecmp('opcache', $name) === 0) {
                // special case
                $name = 'Zend ' . $name;
            }
            $installed = phpversion($name) ? : '';
            $provided = $extension->getVersion();

            if ($installed !== '' && $installed !== $provided) {
                $extensions[] = $extension;
            }
        }

        return new Platform(
            $platform->getDescription(),
            $platform->getVersion(),
            $platform->getCreatedAt(),
            $extensions
        );
    }
}
