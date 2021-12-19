<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\ContainerFactory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/bootstrap.php';

$container = (new ContainerFactory())->create();

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
