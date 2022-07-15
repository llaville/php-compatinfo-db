<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Bartlett\CompatInfoDb\Infrastructure\Framework\Composer\InstalledVersions;
use Bartlett\CompatInfoDb\Presentation\Console\Command\AbstractCommand;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use Phar;
use function sprintf;

/**
 * Symfony Console Application to handle the CompatInfo database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class Application extends SymfonyApplication implements ApplicationInterface
{
    use ContainerAwareTrait;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(
            self::NAME,
            $this->getInstalledVersion(false)
        );
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
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if (null === $input) {
            if ($this->container->has(InputInterface::class)) {
                $input = $this->container->get(InputInterface::class);
            } else {
                $input = new ArgvInput();
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
            $phar = new Phar($_SERVER['argv'][0]);
            $output->writeln($phar->getMetadata());
            return AbstractCommand::SUCCESS;
        }

        return parent::run($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    public function getHelp(): string
    {
        return sprintf(
            '<info>%s</info> version <comment>%s</comment>',
            $this->getName(),
            $this->getVersion()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getLongVersion(): string
    {
        return $this->getInstalledVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function getInstalledVersion(bool $withRef = true): ?string
    {
        return InstalledVersions::getPrettyVersion('bartlett/php-compatinfo-db', $withRef);
    }

    /**
     * {@inheritDoc}
     */
    public function getApplicationParameters(): array
    {
        /** @var Container $container */
        $container = $this->container;
        return $container->getParameterBag()->all();
    }
}
