<?php declare(strict_types=1);

/**
 * Handler to return information about current installation components.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Doctor;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionFactory;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;

use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

use Generator;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use function array_search;
use function count;
use function sprintf;
use function trim;
use const PHP_VERSION;

/**
 * @since Release 3.6.0
 */
final class DoctorHandler implements QueryHandlerInterface
{
    private const CONSTRAINT_SKIPPED = 'skipped';
    private const CONSTRAINT_PASSED  = 'passed';

    /** @var ExtensionFactory */
    private $factory;

    /** @var array<string, array> */
    private $dependencies;

    /** @var array<string, array> */
    private $requirements;

    use LibraryVersionProviderTrait;

    /**
     * ShowHandler constructor.
     *
     * @param ExtensionFactory $extensionFactory
     */
    public function __construct(ExtensionFactory $extensionFactory)
    {
        $this->factory = $extensionFactory;
    }

    /**
     * @param DoctorQuery $query
     * @return array[]
     */
    public function __invoke(DoctorQuery $query): array
    {
        $withTests = $query->withTests();
        if ($withTests) {
            $executableFinder = new ExecutableFinder();
            $phpunitBin = $executableFinder->find('phpunit', 'vendor/bin/simple-phpunit');
        } else {
            $phpunitBin = null;
        }

        $extensions = $query->getExtensions();

        $report = [
            'CompatInfoDB' => [
                'version' => ApplicationInterface::VERSION,
            ],
            'PHP' => [
                'version' => PHP_VERSION,
                'extensions' => count($extensions),
                'constraints' => [],
            ],
        ];

        $this->requirements = [];

        foreach ($extensions as $name) {
            if ($withTests) {
                $process = new Process([$phpunitBin, '--testsuite=' . $name, '--testdox']);
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
                'installed' => $installed ? sprintf('Yes (%s)', $installed): 'No',
                'dependencies' => $this->dependencies,
            ];

            if ($withTests) {
                /** @var Process<int, Generator> $process */
                while ($process->isRunning()) {
                    // waiting for process to finish
                }
                $report[$name]['tests'] = [$process->getCommandLine() => $process->getOutput()];
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
        }

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
            trim((string) (new VersionParser)->parseConstraints($constraint), '[]')
        );

        if (Semver::satisfies($ver, $constraint)) {
            $this->dependencies[$name][self::CONSTRAINT_PASSED][$constraint] = $prettyConstraint;
            $this->requirements[$name][self::CONSTRAINT_PASSED] += 1;
        } else {
            $this->dependencies[$name][self::CONSTRAINT_SKIPPED][$constraint] = $prettyConstraint;
            $this->requirements[$name][self::CONSTRAINT_SKIPPED] += 1;
        }
    }
}
