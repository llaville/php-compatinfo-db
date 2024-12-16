<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Kernel;

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\CompatInfoDbExtension;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use function get_parent_class;
use function getcwd;
use function is_dir;
use function is_writable;
use function mkdir;
use function preg_match;
use function realpath;
use function sprintf;
use function str_contains;
use function str_replace;
use function time;
use function ucfirst;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
abstract class AbstractKernel implements KernelInterface, MicroKernelInterface
{
    use MicroKernelTrait;

    /**
     * @var string[]
     */
    protected array $configFiles;
    /**
     * @var CompilerPassInterface[]
     */
    protected array $compilerPasses;
    /**
     * @var ExtensionInterface[]
     */
    protected array $extensions;

    public function __construct(string $environment, bool $debug)
    {
        $this->environment = $environment;
        $this->debug = $debug;
        $this->projectDir = null;
    }

    /**
     * @inheritDoc
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        return $this->create($configFiles, [new MessengerPass()], [new CompatInfoDbExtension()]);
    }

    /**
     * @inheritDoc
     */
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []): ContainerInterface
    {
        $this->configFiles = $configFiles;
        $this->compilerPasses = $compilerPasses;
        $this->extensions = $extensions;

        $this->boot();

        return $this->container;
    }

    /**
     * Gets the container class.
     *
     * @throws InvalidArgumentException If the generated class name is invalid
     */
    protected function getContainerClass(): string
    {
        $class = static::class;
        $class = str_contains($class, "@anonymous\0")
            ? get_parent_class($class) . str_replace('.', '_', ContainerBuilder::hash($class))
            : $class;
        $class = str_replace('\\', '_', $class) . ucfirst($this->environment) . ($this->debug ? 'Debug' : '') . 'Container';

        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The environment "%s" contains invalid characters, it can only contain characters allowed in PHP class names.',
                    $this->environment
                )
            );
        }

        return $class;
    }

    /**
     * Initializes the service container.
     *
     * The built version of the service container is used when fresh, otherwise the
     * container is built.
     *
     * @throws Exception
     */
    protected function initializeContainer(): void
    {
        $class = $this->getContainerClass();
        $cacheDir = $this->getCacheDir();

        // @see https://symfony.com/doc/current/components/config/caching.html
        $cache = new ConfigCache($cacheDir . DIRECTORY_SEPARATOR . $class . '.php', $this->debug);
        $cachePath = $cache->getPath();

        if (!$cache->isFresh()) {
            $container = $this->getContainerBuilder();
            $this->dumpContainer($cache, $container, $class, $this->getContainerBaseClass());
        }

        require_once $cachePath;
        $this->container = new $class();
        $this->container->set('kernel', $this);
    }

    /**
     * Builds the service container.
     *
     * @throws RuntimeException|Exception
     */
    protected function buildContainer(): ContainerBuilder
    {
        foreach (['cache' => $this->getCacheDir(), 'logs' => $this->getLogDir()] as $name => $dir) {
            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new RuntimeException(sprintf('Unable to create the "%s" directory (%s).', $name, $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new RuntimeException(sprintf('Unable to write in the "%s" directory (%s).', $name, $dir));
            }
        }

        $container = new ContainerBuilder(new ParameterBag($this->getKernelParameters()));
        $container->addObjectResource($this);
        $this->prepareContainer($container);
        $this->registerContainerConfiguration($this->getContainerLoader($container));

        return $container;
    }

    /**
     * Prepares the ContainerBuilder before it is compiled.
     */
    protected function prepareContainer(ContainerBuilder $container): void
    {
        foreach ($this->compilerPasses as $compilerPass) {
            if ($compilerPass instanceof CompilerPassInterface) {
                $container->addCompilerPass($compilerPass);
            }
        }

        foreach ($this->extensions as $extension) {
            if ($extension instanceof ExtensionInterface) {
                $container->registerExtension($extension);
            }
        }

        foreach ($container->getExtensions() as $extension) {
            $container->loadFromExtension($extension->getAlias(), []);
        }
    }

    /**
     * Loads the container configuration.
     *
     * @throws Exception|FileLocatorFileNotFoundException
     */
    protected function registerContainerConfiguration(LoaderInterface $loader): void
    {
        foreach ($this->configFiles as $configFile) {
            if ($loader->supports($configFile)) {
                $loader->load($configFile);
            } else {
                throw new FileLocatorFileNotFoundException(
                    sprintf('Format of file "%s" is not supported.', $configFile)
                );
            }
        }
    }

    /**
     * Dumps the service container to PHP code in the cache.
     *
     * @param string $class The name of the class to generate
     * @param string $baseClass The name of the container's base class
     */
    protected function dumpContainer(ConfigCacheInterface $cache, ContainerBuilder $container, string $class, string $baseClass): void
    {
        // cache the container
        $dumper = new PhpDumper($container);

        // @var string|array A PHP class representing the service container or an array of PHP files if the "as_files" option is set
        $content = $dumper->dump([
            'class' => $class,
            'base_class' => $baseClass,
            'file' => $cache->getPath(),
            'as_files' => false,
            'debug' => $this->debug,
            'build_time' => $container->hasParameter('kernel.container_build_time')
                ? $container->getParameter('kernel.container_build_time') : time(),
        ]);
        $rootCode = $content;

        $cache->write($rootCode, $container->getResources());
    }

    /**
     * Returns a loader for the container.
     */
    protected function getContainerLoader(ContainerBuilder $container): LoaderInterface
    {
        $paths = [
            getcwd(),
            implode(DIRECTORY_SEPARATOR, [$this->getConfigDir(), 'set']),
        ];
        return new PhpFileLoader($container, new FileLocator($paths), $this->getEnvironment());
    }

    /**
     * Gets the container's base class.
     */
    protected function getContainerBaseClass(): string
    {
        return 'Container';
    }

    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @throws RuntimeException|Exception
     */
    public function getContainerBuilder(): ContainerBuilder
    {
        $container = $this->buildContainer();
        $container->compile();  // mandatory to populate collections (sniff and polyfill)
        return $container;
    }

    /**
     * Returns the kernel parameters.
     *
     * @return array<string, mixed>
     */
    protected function getKernelParameters(): array
    {
        return [
            'kernel.environment' => $this->environment,
            'kernel.debug' => $this->debug,
            'kernel.home_dir' => $this->getHomeDir(),
            'kernel.project_dir' => $this->getProjectDir(),
            'kernel.cache_dir' => realpath($this->getCacheDir()),
            'kernel.build_dir' => realpath($this->getCacheDir()),
            'kernel.logs_dir' => realpath($this->getLogDir()),
            'kernel.vendor_dir' => $this->getProjectDir() . DIRECTORY_SEPARATOR . 'vendor',
            'kernel.container_class' => $this->getContainerClass(),
        ];
    }
}
