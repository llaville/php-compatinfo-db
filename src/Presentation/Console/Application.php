<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Bartlett\CompatInfoDb\DatabaseFactory;
use Bartlett\CompatInfoDb\Presentation\Console\Command\BuildExtensionCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\InitCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ListCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\PublishCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Presentation\Console\Command\ShowCommand;

use Psr\Container\ContainerInterface;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use PDO;

/**
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class Application extends \Symfony\Component\Console\Application
{
    public const NAME = 'Database handler for CompatInfo';
    public const VERSION = '2.13.0';

    /** @var string */
    private $baseDir;

    /** @var ContainerInterface  */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        try {
            $version = \Jean85\PrettyVersions::getVersion('bartlett/php-compatinfo-db')->getPrettyVersion();
        } catch (\OutOfBoundsException $e) {
            $version = self::VERSION;
        }
        parent::__construct(self::NAME, $version);

        $this->container = $container;
        $this->setCommandLoader($this->createCommandLoader($container));
        $this->baseDir = dirname(__DIR__, 3);
    }

    public function getDbFilename() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'compatinfo.sqlite';
    }

    public function getRefDir() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'references';
    }

    public function getLongVersion() : string
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                $v = $this->getDbVersions();

                return sprintf(
                    '<info>%s</info> version <comment>%s</comment> DB built <comment>%s</comment>',
                    $this->getName(),
                    $this->getVersion(),
                    $v['build.string']
                );
            }

            return $this->getName();
        }

        return 'Console Tool';
    }

    public function getDbVersions() : array
    {
        $pdo = DatabaseFactory::create('sqlite');

        $stmt = $pdo->prepare(
            'SELECT build_string as "build.string", build_date as "build.date", build_version as "build.version"' .
            ' FROM bartlett_compatinfo_versions'
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->container->set(InputInterface::class, $input);
        $this->container->set(OutputInterface::class, $output);

        return parent::doRun($input, $output);
    }
    /**
     * @param ContainerInterface $container
     * @return CommandLoaderInterface
     * @see https://symfony.com/doc/current/console/lazy_commands.html#containercommandloader
     */
    private function createCommandLoader(ContainerInterface $container): CommandLoaderInterface
    {
        return new ContainerCommandLoader(
            $container,
            [
                BuildExtensionCommand::NAME => BuildExtensionCommand::class,
                DiagnoseCommand::NAME => DiagnoseCommand::class,
                InitCommand::NAME => InitCommand::class,
                ListCommand::NAME => ListCommand::class,
                PublishCommand::NAME => PublishCommand::class,
                ReleaseCommand::NAME => ReleaseCommand::class,
                ShowCommand::NAME => ShowCommand::class,
            ]
        );
    }
}
