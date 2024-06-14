<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

/**
 * Shows short information about this package.
 *
 * @since Release 3.20.0
 * @author Laurent Laville
 */
final class AboutCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'about';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Shows short information about this package')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $defaultVersion = '6.7';

        $lines = [
            sprintf(
                '<info>%s</info> version <comment>%s</comment>',
                $app->getName(),
                $app->getApplicationParameters()['compat_info_db.version'] ?? $defaultVersion
            ),
            sprintf(
                '<comment>Please visit %s/%s/ for more information.</comment>',
                'https://llaville.github.io/php-compatinfo-db',
                $defaultVersion
            ),
        ];
        $io->text($lines);

        return self::SUCCESS;
    }
}
