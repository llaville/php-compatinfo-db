<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\ContainerFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

return (new ContainerFactory())->create();
