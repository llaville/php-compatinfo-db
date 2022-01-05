<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader as SymfonyFactoryCommandLoader;

use Phar;
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
    public function __construct(iterable $commands)
    {
        $factories = [];

        if (Phar::running()) {
            // these commands are disallowed in PHAR distribution
            $blacklist = [InitCommand::class, ReleaseCommand::class, BuildCommand::class];
        } else {
            $blacklist = [];
            if (getenv('APP_ENV') === 'prod') {
                $blacklist[] = BuildCommand::class;
            }
        }

        foreach ($commands as $command) {
            if (in_array(get_class($command), $blacklist)) {
                continue;
            }
            $factories[$command->getName()] = function () use ($command) {
                return $command;
            };
        }

        parent::__construct($factories);
    }
}
