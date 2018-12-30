<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

class BackupHandler implements CommandHandlerInterface
{
    public function __invoke(BackupCommand $command): bool
    {
        $source = $command->source;

        $sha1 = sha1_file($source);

        if (empty($command->target)) {
            $dest = dirname(dirname($source)) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . basename($source) . ".$sha1";
            $command->target = $dest;
        } else {
            $dest = $command->target;
        }

        return copy($source, $dest);
    }
}
