<?php declare(strict_types=1);

/**
 * List all references supported.
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

use Bartlett\CompatInfoDb\Application\Query\ListRef\ListQuery;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableSeparator;

use function phpversion;
use function sprintf;
use function strcasecmp;
use function version_compare;

/**
 * @since Release 2.0.0RC1
 */
final class ListCommand extends AbstractCommand implements CommandInterface
{
    use ExtensionVersionProviderTrait;

    public const NAME = 'db:list';

    protected function configure(): void
    {
        $this->setName(self::NAME)
            ->setDescription('List all references supported in the Database')
            ->addOption('all', 'a', null, 'List all references')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listQuery = new ListQuery(
            $input->getOption('all'),
            !$input->getOption('all'),
            ApplicationInterface::VERSION
        );

        /** @var Platform $platform */
        $platform = $this->queryBus->query($listQuery);

        $rows = [];
        $extensions = $platform->getExtensions();
        foreach ($extensions as $extension) {
            $name = $extension->getName();
            if (strcasecmp('opcache', $name) === 0) {
                // special case
                $name = 'Zend ' . $name;
            }

            $provided = $extension->getVersion();
            $installed = phpversion($name) ? : '';

            $row = [
                $extension->getDescription(),
                $extension->getType(),
                $extension->getName(),
                $provided,
                version_compare($provided, $installed, 'eq') ? $installed : '<comment>'.$installed.'</comment>',
                $extension->isDeprecated() ? 'no more supported' : '',
            ];

            $rows[] = $row;
        }

        $headers = ['Description', 'Type', 'Name', 'Provided', 'Installed', 'Comment'];
        $footers = [
            sprintf('<info>Total [%d]</info>', count($extensions)),
            '',
            '',
            '',
            '',
            '',
        ];

        $rows[] = new TableSeparator();
        $rows[] = $footers;

        $io = new Style($input, $output);
        $io->title('Reference List');
        $io->table($headers, $rows);
        $io->note(
            sprintf(
                'Platform "%s %s" built %s',
                $platform->getDescription(),
                $platform->getVersion(),
                $platform->getCreatedAt()->format('c')
            )
        );

        return self::SUCCESS;
    }
}
