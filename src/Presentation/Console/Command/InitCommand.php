<?php declare(strict_types=1);

/**
 * Initialize the database with JSON files for all extensions.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\Init\InitQuery;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @since Release 2.0.0RC1
 */
class InitCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'db:init';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Load JSON file(s) into database')
            ->addArgument(
                'rel_version',
                InputArgument::OPTIONAL,
                'New DB version'
            )
            ->addOption('force', 'f', null, 'Reset database contents even if not empty')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new Style($input, $output);
        $io->caution('This operation should not be executed in a production environment!');

        $relVersion = $input->getArgument('rel_version') ?? null;

        if (null === $relVersion) {
            $appVersion = $this->getApplication()->getVersion();
        } else {
            $appVersion = trim($relVersion);
        }
        $initQuery = new InitQuery($appVersion, $io, $input->getOption('force'));

        $exitCode = $this->queryBus->query($initQuery);

        if (0 === $exitCode) {
            $io->success('Database built successfully!');
        } else {
            $io->warning('Database already exists.');
            $io->note('Use --force option to replace contents.');
        }

        return $exitCode;
    }
}
