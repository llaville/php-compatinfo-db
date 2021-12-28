<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\Init\InitQuery;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialize the database with JSON files for all extensions.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
class InitCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:init';

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('Load JSON file(s) into database')
            ->addArgument(
                'rel_version',
                InputArgument::OPTIONAL,
                'New DB version'
            )
            ->addOption('force', 'f', null, 'Reset database contents even if not empty')
            ->addOption('progress', null, null, 'Show progress bar')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->caution('This operation should not be executed in a production environment!');

        $relVersion = $input->getArgument('rel_version') ?? null;

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        if (null === $relVersion) {
            $appVersion = $app->getInstalledVersion();
        } else {
            $appVersion = trim($relVersion);
        }
        $initQuery = new InitQuery($appVersion, $io, $input->getOption('force'), $input->getOption('progress'));

        $exitCode = $this->queryBus->query($initQuery);

        if (self::SUCCESS === $exitCode) {
            $io->success('Database built successfully!');
        } else {
            $io->warning('Database already exists.');
            $io->note('Use --force option to replace contents.');
        }

        return $exitCode;
    }
}
