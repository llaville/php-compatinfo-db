<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillCommand as AppPolyfillCommand;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

use function trim;

/**
 * Update JSON files when a new Polyfill package is added.
 *
 * @since Release 4.2.0
 * @author Laurent Laville
 */
final class PolyfillCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:polyfill';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Add new Polyfill elements')
            ->addArgument(
                'package',
                InputArgument::REQUIRED,
                'Polyfill vendor/package identifier'
            )
            ->addArgument(
                'tag',
                InputArgument::REQUIRED,
                'Polyfill tag identifier'
            )
            ->addOption(
                'php',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Polyfill for PHP version'
            )
            ->addOption(
                'whitelist',
                null,
                InputOption::VALUE_REQUIRED,
                'File to scan into package',
                'bootstrap.php'
            )
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new Style($input, $output);

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $kernel = $app->getKernel();

        $polyfillCommand = new AppPolyfillCommand(
            trim($input->getArgument('package')),
            trim($input->getArgument('tag')),
            $input->getOption('php'),
            $io,
            $kernel->getCacheDir(),
            $input->getOption('whitelist')
        );

        try {
            $this->commandBus->handle($polyfillCommand);
        } catch (HandlerFailedException $e) {
            $exceptions = [];
            foreach ($e->getWrappedExceptions() as $exception) {
                $exceptions[] = $exception->getMessage();
                if ($io->isDebug()) {
                    $exceptions[] = sprintf(
                        '<comment>from file "%s" at line %d</comment>',
                        $exception->getFile(),
                        $exception->getLine()
                    );
                }
            }
            $io->error('Cannot add polyfill for following reason(s)');
            $io->listing($exceptions);
            return self::FAILURE;
        }

        $io->success('New polyfill was added in JSON files');
        return self::SUCCESS;
    }
}
