<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Query\ListRef\ListQuery;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableSeparator;

use function phpversion;
use function sprintf;
use function strcasecmp;
use function version_compare;

/**
 * List all references supported.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
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
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Filter extension by type')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Filter extension by name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filters = [];
        $type = $input->getOption('type');
        if (!empty($type)) {
            $filters['type'] = $type;
        }
        $name = $input->getOption('name');
        if (!empty($name)) {
            $filters['name'] = $name;
        }

        /** @var ApplicationInterface $app */
        $app = $this->getApplication();

        $listQuery = new ListQuery(
            $input->getOption('all'),
            !$input->getOption('all'),
            $app->getInstalledVersion(),
            $filters
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
                version_compare($provided, $installed, 'eq') ? $installed : '<comment>' . $installed . '</comment>',
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
