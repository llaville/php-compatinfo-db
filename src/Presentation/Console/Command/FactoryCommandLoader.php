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
    public function __construct(iterable $commands, bool $isDevMode)
    {
        $factories = [];

        if (Phar::running() || !$isDevMode) {
            // these commands are disallowed in PHAR distribution
            $blacklist = [ReleaseCommand::class, BuildCommand::class];
        } else {
            $blacklist = [];
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
