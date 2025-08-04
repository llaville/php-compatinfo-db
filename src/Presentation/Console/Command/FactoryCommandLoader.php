<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Presentation\Console\Command\Debug\ContainerDebugCommand;

use Doctrine\DBAL\Tools\Console\Command\RunSqlCommand;
use Doctrine\ORM\Tools\Console\Command\InfoCommand;
use Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

use Symfony\Bundle\FrameworkBundle\Command\EventDispatcherDebugCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader as SymfonyFactoryCommandLoader;
use Symfony\Component\Messenger\Command\DebugCommand;

use Phar;
use function class_exists;
use function get_class;
use function in_array;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class FactoryCommandLoader extends SymfonyFactoryCommandLoader
{
    /**
     * FactoryCommandLoader constructor.
     *
     * @param Command[] $commands
     */
    public function __construct(iterable $commands, string $environment)
    {
        $factories = [];
        $blacklist = [];

        if ('prod' === $environment) {
            // these commands are disallowed in production environment
            $blacklist = [
                // Debug commands
                DebugCommand::class,
                // Doctrine commands
                RunSqlCommand::class,
                InfoCommand::class,
                MappingDescribeCommand::class,
                ValidateSchemaCommand::class,
            ];
            if (class_exists(ContainerDebugCommand::class)) {
                // TRUE when symfony/framework-bundle is installed
                $blacklist[] = ContainerDebugCommand::class;
            }
            if (class_exists(EventDispatcherDebugCommand::class)) {
                // TRUE when symfony/framework-bundle is installed
                $blacklist[] = EventDispatcherDebugCommand::class;
            }
        }

        if (Phar::running()) {
            // these commands are disallowed in PHAR distribution
            $blacklist[] = ReleaseCommand::class;
            $blacklist[] = BuildCommand::class;
        }

        foreach ($commands as $command) {
            if (in_array(get_class($command), $blacklist)) {
                continue;
            }
            $factories[$command->getName()] = static fn(): Command => $command;
        }

        parent::__construct($factories);
    }
}
