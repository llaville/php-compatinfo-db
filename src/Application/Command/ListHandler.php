<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\ExtensionFactory;

class ListHandler implements CommandHandlerInterface
{
    public function __invoke(ListCommand $command): array
    {
        $factory = new ExtensionFactory('');
        $refs    = $factory->getExtensions();
        $loaded  = 0;
        $rows    = array();

        foreach ($refs as $ref) {
            $rows[] = array(
                $ref->name,
                $ref->version,
                $ref->state,
                $ref->date,
                $ref->loaded,
            );
            if (!empty($ref->loaded)) {
                $loaded++;
            }
        }
        return [$rows, count($refs), $loaded];
    }
}
