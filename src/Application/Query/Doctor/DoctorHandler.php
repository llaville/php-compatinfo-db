<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Doctor;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactoryInterface;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

use function array_search;
use function count;
use function phpversion;
use function sprintf;
use function strcasecmp;
use function trim;
use const PHP_VERSION;

/**
 * Handler to return information about current installation components.
 *
 * @since Release 3.6.0
 * @author Laurent Laville
 */
final class DoctorHandler implements QueryHandlerInterface
{
    use LibraryVersionProviderTrait;

    private const CONSTRAINT_SKIPPED = 'skipped';
    private const CONSTRAINT_PASSED  = 'passed';
    /** @var array<string, mixed> */
    private array $dependencies;
    /** @var array<string, mixed> */
    private array $requirements;

    /**
     * ShowHandler constructor.
     */
    public function __construct(
        private readonly ExtensionFactoryInterface $factory
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(DoctorQuery $query): array
    {
        $withTests = $query->withTests();
        if ($withTests) {
            $executableFinder = new ExecutableFinder();
            $phpunitBin = $executableFinder->find('phpunit');
        } else {
            $phpunitBin = null;
        }

        $extensions = $query->getExtensions();

        $report = [
            'CompatInfoDB' => [
                'version' => $query->getVersion(),
            ],
            'PHP' => [
                'version' => PHP_VERSION,
                'extensions' => count($extensions),
                'dependencies' => [],
                'constraints' => [],
            ],
        ];
        $reportStatus = 0;

        $this->requirements = [];

        foreach ($extensions as $name) {
            if ($withTests) {
                $process = new Process([$phpunitBin, '--testsuite=' . $name, '--testdox', '--no-progress']);
                $process->start();
            } else {
                $process = null;
            }

            $extension = $this->factory->create($name);

            if (strcasecmp('opcache', $name) === 0) {
                // special case
                $installed = phpversion('Zend ' . $name) ? : false;
            } else {
                $installed = phpversion($name) ? : false;
            }

            $this->dependencies = [];
            foreach ($extension->getDependencies() as $dependency) {
                $this->addDependency($dependency);
            }

            $report[$name] = [
                'description' => $extension->getDescription(),
                'type' => $extension->getType(),
                'version supported' => $extension->getVersion(),
                'installed' => $installed ? sprintf('Yes (%s)', $installed) : 'No',
                'dependencies' => $this->dependencies,
            ];

            if ($withTests) {
                while ($process->isRunning()) {
                    // waiting for process to finish
                }
                $report[$name]['tests'] = [$process->getCommandLine() => $process->getOutput()];

                if ($process->getExitCode() > 0) {
                    $reportStatus = 2;
                }
            }
        }

        /**
         * @var string $name
         * @var string[] $result
         */
        foreach ($this->requirements as $name => $result) {
            $report['PHP']['constraints'][$name] = sprintf(
                '%d passed, %d skipped',
                $result[self::CONSTRAINT_PASSED],
                $result[self::CONSTRAINT_SKIPPED]
            );
            if ($result[self::CONSTRAINT_SKIPPED] > 0) {
                $reportStatus = $reportStatus | 1;
            }
            $report['PHP']['dependencies'][$name] = $result['version'];
        }
        $report['status'] = $reportStatus;

        return $report;
    }

    private function addDependency(Dependency $dependency): void
    {
        $name = $dependency->getName();
        $constraint = $dependency->getConstraint();
        $ver = $this->getPrettyVersion($name);
        if ($ver === '') {
            // dependency is unknown or does not provides any version number
            return;
        }

        if (!isset($this->dependencies[$name])) {
            $this->dependencies[$name] = ['version' => $ver, self::CONSTRAINT_PASSED => [], self::CONSTRAINT_SKIPPED => []];
        }
        if (array_search($constraint, $this->dependencies[$name][self::CONSTRAINT_PASSED]) !== false) {
            return;
        }
        if (array_search($constraint, $this->dependencies[$name][self::CONSTRAINT_SKIPPED]) !== false) {
            return;
        }

        if (!isset($this->requirements[$name])) {
            $this->requirements[$name] = [self::CONSTRAINT_PASSED => 0, self::CONSTRAINT_SKIPPED => 0];
        }

        $prettyConstraint = sprintf(
            '%s [%s]',
            $constraint,
            trim((string) (new VersionParser())->parseConstraints($constraint), '[]')
        );

        if (Semver::satisfies($ver, $constraint)) {
            $this->dependencies[$name][self::CONSTRAINT_PASSED][$constraint] = $prettyConstraint;
            $this->requirements[$name][self::CONSTRAINT_PASSED] += 1;
        } else {
            $this->dependencies[$name][self::CONSTRAINT_SKIPPED][$constraint] = $prettyConstraint;
            $this->requirements[$name][self::CONSTRAINT_SKIPPED] += 1;
        }
        $this->requirements[$name]['version'] = $ver;
    }
}
