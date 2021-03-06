<?php declare(strict_types=1);

/**
 * Symfony Console Application to handle the CompatInfo database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console;

use PackageVersions\Versions;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use Phar;
use function basename;
use function dirname;
use function explode;
use function is_callable;
use function sprintf;
use function strpos;
use function substr_count;

/**
 * @since Release 2.0.0RC1
 */
class Application extends SymfonyApplication implements ApplicationInterface
{
    /** @var ContainerInterface  */
    private $container;

    /**
     * Application constructor.
     *
     * @param string $version (optional) auto-detect
     */
    public function __construct(string $version = 'UNKNOWN')
    {
        if ('UNKNOWN' === $version) {
            // composer or git outside world strategy
            $version = self::VERSION;
        } elseif (substr_count($version, '.') === 2) {
            // release is in X.Y.Z format
        } else {
            // composer or git strategy
            $version = Versions::getVersion('bartlett/php-compatinfo-db');
            list($ver, ) = explode('@', $version);

            if (strpos($ver, 'dev') === false) {
                $version = $ver;
            }
        }
        parent::__construct(self::NAME, $version);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        if (Phar::running()) {
            $definition->addOption(
                new InputOption(
                    'manifest',
                    null,
                    InputOption::VALUE_NONE,
                    'Show which versions of dependencies are bundled'
                )
            );
            // handle external configuration files is not allowed with PHAR distribution
            return $definition;
        }
        $definition->addOption(
            new InputOption(
                'config',
                'c',
                InputOption::VALUE_REQUIRED,
                'Read configuration from PHP file'
            )
        );
        $definition->addOption(
            new InputOption(
                'no-configuration',
                null,
                InputOption::VALUE_NONE,
                'Ignore current configuration and run with only required services (config/set/common.php)'
            )
        );
        $definition->addOption(
            new InputOption(
                'profile',
                null,
                InputOption::VALUE_NONE,
                'Display timing and memory usage information'
            )
        );
        return $definition;
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            if ($this->container->has(InputInterface::class)) {
                $input = $this->container->get(InputInterface::class);
            } else {
                $input = new ArgvInput();
            }


            if ($input->hasParameterOption('--no-configuration')) {
                $configFile = 'config/set/common.php';
            } else {
                $configFile = $input->getParameterOption('-c');
            }

            if (false === $configFile) {
                $configFile = $input->getParameterOption('--config');
            }
            if (false !== $configFile) {
                $containerBuilder = new ContainerBuilder();
                try {
                    $loader = new PhpFileLoader($containerBuilder, new FileLocator(dirname($configFile)));
                    $loader->load(basename($configFile));
                } catch (FileLocatorFileNotFoundException $e) {
                    $output = new ConsoleOutput();
                    $this->renderThrowable($e, $output);
                    return 1;
                }
                $containerBuilder->compile();
                $this->setContainer($containerBuilder);
            }
        }

        if (null === $output) {
            if ($this->container->has(OutputInterface::class)) {
                $output = $this->container->get(OutputInterface::class);
            } else {
                $output = new ConsoleOutput();
            }
        }

        if ($input->hasParameterOption('--manifest')) {
            $phar = new Phar('compatinfo-db.phar');
            $manifest = $phar->getMetadata();
            if (is_callable($manifest)) {
                $manifest = $manifest();
            }
            $output->writeln($manifest);
            return 0;
        }

        return parent::run($input, $output);
    }

    /**
     * {@inheritDoc}
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getLongVersion(): string
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                return sprintf(
                    '<info>%s</info> version <comment>%s</comment>',
                    $this->getName(),
                    $this->getVersion()
                );
            }
            return $this->getName();
        }
        return 'Console Tool';
    }
}
