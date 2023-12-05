<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

use function sprintf;

/**
 * Symfony Console Application to handle the CompatInfo database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class Application extends SymfonyApplication implements ApplicationInterface
{
    protected ?ContainerInterface $container;

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
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
    public function getVersion(): string
    {
        $version = parent::getVersion();

        if ('UNKNOWN' == $version) {
            $version = $this->getLongVersion();
        }
        return $version;
    }

    /**
     * {@inheritDoc}
     */
    public function getLongVersion(): string
    {
        return $this->getApplicationParameters()['compat_info_db.version'];
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

    /**
     * {@inheritDoc}
     */
    public function getKernel(): object
    {
        return $this->container->get('kernel');
    }
}
