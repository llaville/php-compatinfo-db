<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Symfony\Component\Console\CommandLoader\FactoryCommandLoader as SymfonyFactoryCommandLoader;

use Phar;
use function in_array;

class FactoryCommandLoader extends SymfonyFactoryCommandLoader
{
    public function __construct(iterable $commands)
    {
        $factories = [];

        if (Phar::running()) {
            // these commands are disallowed in PHAR distribution
            $blacklist = [InitCommand::NAME, ReleaseCommand::NAME];
        } else {
            $blacklist = [];
        }

        foreach ($commands as $references) {
            foreach ($references as $command) {
                if (in_array($command->getName(), $blacklist)) {
                    continue;
                }
                $factories[$command->getName()] = function () use ($command) {
                    return $command;
                };
            }
        }

        parent::__construct($factories);
    }
}
