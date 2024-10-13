<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Kernel;

use Bartlett\CompatInfoDb\Application\Configuration\ConfigResolver;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\AbstractCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Throwable;
use function implode;
use const DIRECTORY_SEPARATOR;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 *
 * @link https://tomasvotruba.com/blog/introducing-light-kernel-for-symfony-console-apps/
 */
final class ConsoleKernel extends AbstractKernel implements ConsoleKernelInterface
{
    /**
     * {@inheritDoc}
     */
    public function getCacheDir(?string $default = null): string
    {
        $default = $default ?? implode(DIRECTORY_SEPARATOR, [$this->getHomeDir(), '.cache', 'bartlett']);
        return parent::getCacheDir($default) . DIRECTORY_SEPARATOR . $this->environment;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(?InputInterface $input = null): int
    {
        $container = $this->createFromInput($input);

        try {
            $app = $container->get(ApplicationInterface::class);
            return $app->run();
        } catch (Throwable $e) {
            if (null === $input) {
                $input = new ArgvInput();
            }
            $output = new ConsoleOutput();
            $io = new Style($input, $output);
            $io->error($e->getMessage());
            return AbstractCommand::FAILURE;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createFromInput(?InputInterface $input = null): ContainerInterface
    {
        if (null === $input) {
            $input = new ArgvInput();
        }
        $configResolver = new ConfigResolver($input);

        return $this->createFromConfigs($configResolver->provide());
    }
}
