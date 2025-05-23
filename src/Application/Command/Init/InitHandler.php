<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Init;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Presentation\Console\StyleInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Helper\ProgressBar;

use FilesystemIterator;
use Generator;
use RecursiveDirectoryIterator;
use RuntimeException;
use Throwable;
use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function dirname;
use function implode;
use function in_array;
use function json_last_error;
use function sprintf;
use function str_contains;
use const DIRECTORY_SEPARATOR;
use const JSON_ERROR_NONE;

/**
 * Handler to initialize the database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class InitHandler implements CommandHandlerInterface
{
    private const RETURN_CODE_DISTRIBUTION_PLATFORM_EXISTS = 110;
    private const RETURN_CODE_DATABASE_READONLY = 120;
    private const PHP_RELEASES_7 = ['70', '71', '72', '73', '74'];
    private const PHP_RELEASES_8 = ['80', '81', '82', '83', '84'];

    public function __construct(
        private readonly JsonFileHandler $jsonFileHandler,
        private readonly DistributionRepository $distributionRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(InitCommand $query): void
    {
        $appVersion = $query->getAppVersion();
        $distribution = $this->distributionRepository->getDistributionByVersion($appVersion);

        try {
            if (null !== $distribution) {
                if (!$query->isForce()) {
                    throw new RuntimeException(
                        'Distribution platform already exists. Use `--force` option to reset contents.',
                        self::RETURN_CODE_DISTRIBUTION_PLATFORM_EXISTS
                    );
                }
                $this->distributionRepository->clear();
            }

            $io = $query->getStyle();

            $this->buildDistribution($io, $appVersion, $query->isProgress());
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'readonly database')) {
                $code = self::RETURN_CODE_DATABASE_READONLY;
                $error = 'Attempt to write a readonly database.';
                $conn = $this->entityManager->getConnection();
                $dbParams = $conn->getParams();
                if (in_array($dbParams['driver'], ['sqlite', 'sqlite3', 'pdo_sqlite'])) {
                    $error .= sprintf(' Please check DB file "%s" permissions.', $dbParams['path']);
                }
            } else {
                $code = 0;
                $error = $e->getMessage();
            }
            throw new RuntimeException($error, $code);
        }

        $this->entityManager->clear();
    }

    private function buildDistribution(StyleInterface $io, string $appVersion, bool $withProgressBar): void
    {
        $refDir = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'data', 'reference', 'extension']);
        $flags = FilesystemIterator::SKIP_DOTS;
        $refs = new RecursiveDirectoryIterator($refDir, $flags);

        $extensions = [];

        foreach ($refs as $ref) {
            $extensions[$ref->getBasename()] = $ref->getPathname();
        }
        unset($refs);

        $distributionLabel = "CompatInfoDb $appVersion platform";

        if ($io->isVerbose()) {
            $io->writeln('> Initializing ' . $distributionLabel . ' ...');
        }

        if ($withProgressBar) {
            $progress = new ProgressBar($io, count($extensions));
            $progress->setFormat(' %percent:3s%% %elapsed:6s% %memory:6s% %message%');
            $progress->setMessage('');
            $progress->start();
        }

        $collection = new ArrayCollection();

        foreach ($this->majorReleaseDefinitionProvider() as $refName => $definition) {
            if (!isset($extensions[$refName])) {
                // definition still exists, while reference was removed from file system
                continue;
            }
            $refPathname = $extensions[$refName];

            $component = 'extensions';
            $message = sprintf("Building %s (%s)", $component, $refName);
            if ($withProgressBar) {
                $progress->setMessage($message);
                $progress->display();
            } elseif ($io->isVerbose()) {
                $io->writeln('> ' . $message . ' ...');
            }

            $meta = $this->jsonFileHandler->read($refPathname, $component, '');
            if (null === $meta) {
                // should not be occurs in real condition
                if ($withProgressBar) {
                    $progress->advance();
                }
                continue;
            }

            $collection->add($this->buildExtension($meta, $definition, $refPathname));

            unset($extensions[$refName]);
            if ($withProgressBar) {
                $progress->advance();
            }
        }

        $message = 'Flushing all changes to the database ...';
        if ($withProgressBar) {
            $progress->setMessage($message);
            $progress->display();
        } elseif ($io->isVerbose()) {
            $io->writeln('> ' . $message);
        }

        $distribution = $this->distributionRepository->initialize($collection, $appVersion);

        if ($withProgressBar) {
            $progress->setMessage('');
            $progress->display();
            $progress->finish();
            $progress->clear();
        }

        if ($io->isDebug()) {
            $io->section('Distribution platform');
            $io->text((string) $distribution);

            $io->section('Extension(s) referenced');
            $io->text(
                array_map(
                    function ($item) {
                        return (string) $item;
                    },
                    $distribution->getExtensions()
                )
            );

            $io->section('Configuration(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        function ($item) {
                            return (string) $item;
                        },
                        $extension->getIniEntries()->toArray()
                    )
                );
            }

            $io->section('Constant(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        function ($item) {
                            return (string) $item;
                        },
                        $extension->getConstants()->toArray()
                    )
                );
            }

            $io->section('Function(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        function ($item) {
                            return (string) $item;
                        },
                        $extension->getFunctions()->toArray()
                    )
                );
            }

            $io->section('Class(es) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        function ($item) {
                            return (string) $item;
                        },
                        $extension->getClasses()->toArray()
                    )
                );
            }
        }

        if (count($extensions) > 0) {
            $io->section('Warnings');
            $io->text('Definition not provided for following references:');
            $io->text('');
            $io->listing(array_keys($extensions), ['type' => '[ ]', 'style' => 'fg=red']);
        }
    }

    /**
     * Builds an extension with all its components.
     *
     * @param array<string, string> $meta
     * @param array<string, mixed> $definition
     * @return array<string, mixed>
     */
    private function buildExtension(array $meta, array $definition, string $refPathname): array
    {
        $data = $meta;
        $components = ['releases', 'iniEntries', 'constants', 'functions', 'classes', 'interfaces', 'const', 'methods'];
        foreach ($components as $component) {
            if (isset($definition[$component])) {
                $data[$component] = $this->readData($refPathname, $definition[$component], $component, $meta['type']);
            } else {
                $data[$component] = [];
            }
        }
        return $data;
    }

    /**
     * Reads split JSON data files
     *
     * @param string[] $majorReleases
     * @return string[]
     */
    private function readData(string $path, array $majorReleases, string $fileBasename, string $type): array
    {
        $data = [];

        foreach ($majorReleases as $major) {
            $temp = $this->jsonFileHandler->read($path, $fileBasename, $major);
            if (null === $temp && 'releases' === $fileBasename && 'bundle' === $type) {
                // retrieve core releases
                $temp = $this->jsonFileHandler->read(
                    dirname($path) . DIRECTORY_SEPARATOR . 'core',
                    $fileBasename,
                    $major
                );
            }
            if (!$temp) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    // missing files are optional until all extensions are fully documented
                    continue;
                }
                $error = sprintf('Cannot decode file %s/%s/%s.json', $path, $major, $fileBasename);
                throw new RuntimeException($error);
            }
            $data = array_merge($data, $temp);
        }
        return $data;
    }

    /**
     * Data provider for reference definitions
     *
     * @return Generator<string, mixed>
     */
    private function majorReleaseDefinitionProvider(): Generator
    {
        yield 'amqp' => [
            'classes'    => ['0', '1', '2'],
            'const'      => ['1'],
            'constants'  => ['1', '2'],
            'iniEntries' => ['0', '1', '2'],
            'interfaces' => ['2'],
            'methods'    => ['0', '1', '2'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'apc' => [
            'classes'    => ['3'],
            'constants'  => ['3'],
            'functions'  => ['2', '3'],
            'iniEntries' => ['2', '3'],
            'methods'    => ['3'],
            'releases'   => ['2', '3'],
        ];

        yield 'apcu' => [
            'classes'    => ['5'],
            'constants'  => ['4'],
            'functions'  => ['4', '5'],
            'iniEntries' => ['4'],
            'methods'    => ['5'],
            'releases'   => ['4', '5'],
        ];

        yield 'ast' => [
            'classes'   => ['0'],
            'constants' => ['0', '1'],
            'functions' => ['0'],
            'methods'   => ['0'],
            'releases'  => ['0', '1'],
        ];

        yield 'bcmath' => [
            'classes' => ['84'],
            'functions'  => [
                '40',
                '50',
                '84',
            ],
            'iniEntries' => ['40'],
            'methods'   => ['84'],
            'releases'   => array_merge(
                [
                    '40',
                    '50',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            )
        ];

        yield 'bz2' => [
            'functions' => ['40'],
            'releases'  => array_merge(
                ['40'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'calendar' => [
            'constants' => [
                '40', '43',
                '50',
            ],
            'functions' => ['40'],
            'releases'  => array_merge(
                [
                    '40', '43',
                    '50',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'core' => [
            'classes' => [
                '40',
                '50', '51', '53', '55',
                '70', '71', '73', '74',
                '80', '81', '82', '83', '84',
            ],
            'const' => ['80'],
            'constants' => [
                '40', '42', '43', '44',
                '50', '51', '52', '53', '54', '55',
                '70', '71', '72',
                '84',
            ],
            'functions' => [
                '40', '42', '43',
                '50', '51', '53', '54',
                '70', '73', '74',
                '80', '81', '84',
            ],
            'methods' => [
                '51', '53', '55',
                '70', '72', '74',
                '80', '81', '82', '83', '84',
            ],
            'iniEntries' => [
                '40', '41',
                '50', '54', '55', '56',
                '70', '71', '73', '74',
                '80', '81', '82', '83',
            ],
            'interfaces' => [
                '51', '53',
                '70', '72',
                '80', '81',
            ],
            'releases' => array_merge(
                [
                    '40', '41', '42', '43', '44',
                    '50', '51', '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ctype' => [
            'functions' => ['40'],
            'releases'  => array_merge(
                ['40'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'curl' => [
            'classes'    => [
                '55',
                '80', '81',
            ],
            'constants'  => [
                '40',
                '51', '52', '53', '54', '55', '56',
                '70', '73',
                '81', '82', '83', '84',
            ],
            'functions'  => [
                '40',
                '50', '51', '55',
                '71',
                '82',
            ],
            'iniEntries' => ['53'],
            'methods'    => [
                '55',
                '81',
            ],
            'releases'   => array_merge(
                [
                    '40',
                    '51', '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'date' => [
            'classes'    => [
                '52', '53', '55',
                '83',
            ],
            'const'      => [
                '52', '53',
                '70', '71', '72',
                '82',
            ],
            'constants'  => [
                '51',
                '70',
                '82',
            ],
            'functions'  => [
                '40',
                '50', '51', '52', '53', '55',
            ],
            'iniEntries' => ['51'],
            'interfaces' => ['55'],
            'methods'    => [
                '52', '53', '55', '56',
                '72', '73',
                '80', '82', '83', '84',
            ],
            'releases'   => array_merge(
                [
                    '40',
                    '50', '51', '52', '53', '55',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'dom' => [
            'classes'    => [
                '50', '51',
                '84',
            ],
            'const'      => ['84'],
            'constants'  => [
                '50',
                '84',
            ],
            'functions'  => [
                '50',
                '84',
            ],
            'interfaces' => [
                '80', '84',
            ],
            'methods'    => [
                '50',
                '80', '81', '83', '84',
            ],
            'releases'   => array_merge(
                ['50', '51'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'enchant' => [
            'classes'    => ['80'],
            'constants'  => [
                '1',
                '80',
            ],
            'functions'  => [
                '0', '1',
                '80',
            ],
            'releases'   => array_merge(
                ['0', '1'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ereg' => [
            'functions' => ['40'],
            'releases'  => ['40'],
        ];

        yield 'exif' => [
            'constants'  => ['40'],
            'functions'  => ['40', '42', '43'],
            'iniEntries' => ['43'],
            'releases'   => array_merge(
                ['40', '42', '43'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'fileinfo' => [
            'classes'    => ['0'],
            'constants'  => [
                '0', '1',
                '72',
            ],
            'functions'  => ['0', '1'],
            'methods'    => [
                '0',
                '80',
            ],
            'releases'   => array_merge(
                ['0', '1'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'filter' => [
            'constants'  => [
                '0',
                '70', '71', '73',
                '80', '82',
            ],
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'releases'   => array_merge(
                ['0'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ftp' => [
            'classes'    => ['81'],
            'constants'  => [
                '40', '43',
                '56',
            ],
            'functions'  => [
                '40', '42', '43',
                '50',
                '72',
            ],
            'releases'   => array_merge(
                [
                    '40', '42', '43',
                    '50', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'gd' => [
            'classes'    => ['80', '81'],
            'constants'  => [
                '40',
                '52', '53', '55', '56',
                '72', '74',
                '81',
            ],
            'functions'  => [
                '40', '43',
                '50', '51', '52', '54', '55',
                '72', '74',
                '80', '81',
            ],
            'iniEntries' => ['51'],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '50', '51', '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'gender' => [
            'classes'   => ['0'],
            'const'     => ['0', '1'],
            'methods'   => ['0'],
            'releases'  => ['0', '1'],
        ];

        yield 'geoip' => [
            'constants'  => ['0', '1'],
            'functions'  => ['0', '1'],
            'iniEntries' => ['1'],
            'releases'   => ['0', '1'],
        ];

        yield 'gettext' => [
            'functions'  => ['40', '42'],
            'releases'   => array_merge(
                ['40', '42'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'gmp' => [
            'classes'    => ['80'],
            'constants'  => [
                '40',
                '53', '56',
            ],
            'functions'  => [
                '40',
                '52', '53', '56',
                '70', '73',
            ],
            'methods'    => ['81', '82'],
            'releases'   => array_merge(
                [
                    '40',
                    '52', '53', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'haru' => [
            'classes'  => ['0'],
            'const'    => ['0'],
            'methods'  => ['0', '1'],
            'releases' => ['0', '1'],
        ];

        yield 'hash' => [
            'classes'    => ['72'],
            'constants'  => [
                '1',
                '53', '54',
                '81',
            ],
            'functions'  => [
                '1',
                '53', '55', '56',
                '71', '72',
            ],
            'iniEntries' => [],
            'methods'    => [
                '72',
                '84',
            ],
            'releases'   => array_merge(
                [
                    '1',
                    '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'htscanner' => [
            'iniEntries' => ['0', '1'],
            'releases'   => ['0', '1'],
        ];

        yield 'http' => [
            'classes'    => ['0', '1', '2', '3'],
            'const'      => ['2', '3'],
            'constants'  => ['1', '2', '4'],
            'functions'  => ['1'],
            'iniEntries' => ['1', '2'],
            'interfaces' => ['2'],
            'methods'    => ['2', '3', '4'],
            'releases'   => ['0', '1', '2', '3', '4'],
        ];

        yield 'iconv' => [
            'constants'  => [
                '43',
                '50',
            ],
            'functions'  => [
                '40',
                '50',
            ],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '50',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'igbinary' => [
            'functions'  => ['1'],
            'iniEntries' => ['1'],
            'releases'   => ['1', '2', '3'],
        ];

        yield 'imagick' => [
            'classes'    => ['2', '3'],
            'const'      => ['2', '3'],
            'iniEntries' => ['3'],
            'methods'    => ['2', '3'],
            'releases'   => ['2', '3'],
        ];

        yield 'imap' => [
            'classes'    => [
                '81',
                '1',
            ],
            'constants'  => [
                '40',
                '53',
            ],
            'functions'  => [
                '40', '43',
                '50', '51', '53',
                '82',
                '1',
            ],
            'iniEntries' => ['56'],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '50', '51', '53', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8,
                ['1'],
            ),
        ];

        yield 'inclued' => [
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'releases'   => ['0'],
        ];

        yield 'intl' => [
            'classes'    => [
                '1', '2',
                '55',
                '70',
                '81',
            ],
            'const'      => [
                '1', '2',
                '55',
                '70', '73', '74',
                '80', '83', '84',
            ],
            'constants'  => ['1', '2'],
            'functions'  => [
                '1', '2',
                '55',
                '71', '73',
                '84'
            ],
            'iniEntries' => ['1', '3'],
            'methods'    => [
                '1', '2',
                '55',
                '70', '71', '73',
                '81', '83', '84',
            ],
            'releases'   => array_merge(
                [
                    '1', '2', '3',
                    '55',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'jsmin' => [
            'constants'  => ['0'],
            'functions'  => ['0'],
            'releases'   => ['0', '1', '2', '3'],
        ];

        yield 'json' => [
            'classes'    => ['73'],
            'constants'  => [
                '53', '54', '55', '56',
                '70', '71', '72', '73',
                '81',
            ],
            'functions'  => [
                '52', '53', '55',
                '83',
            ],
            'interfaces' => ['54'],
            'methods'    => ['54'],
            'releases'   => array_merge(
                [
                    '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ldap' => [
            'classes'    => ['81'],
            'constants'  => [
                '40',
                '53', '54', '56',
                '70', '71', '72',
                '84',
            ],
            'functions'  => [
                '40', '42',
                '50', '54', '56',
                '72', '73',
                '80', '83',
            ],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                [
                    '40', '42',
                    '50', '53', '54', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'libevent' => [
            'constants'  => ['0'],
            'functions'  => ['0'],
            'releases'   => ['0'],
        ];

        yield 'libxml' => [
            'classes'    => ['51'],
            'constants'  => [
                '51', '52', '53', '54', '55',
                '70',
                '84',
            ],
            'functions'  => [
                '50', '51', '52', '54',
                '82',
            ],
            'releases'   => array_merge(
                [
                    '51', '52', '53', '54', '55',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'lzf' => [
            'functions'  => ['0', '1'],
            'releases'   => ['0', '1'],
        ];

        yield 'mailparse' => [
            'classes'    => ['0'],
            'constants'  => ['0'],
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'methods'    => ['0', '3'],
            'releases'   => ['0', '2', '3'],
        ];

        yield 'mbstring' => [
            'constants'  => [
                '40',
                '73', '74',
            ],
            'functions'  => [
                '40', '42', '43', '44',
                '50', '52', '53', '54',
                '72', '74',
                '83', '84',
            ],
            'iniEntries' => [
                '40', '42', '43',
                '51',
                '73', '74',
            ],
            'releases'   => array_merge(
                [
                    '40', '42', '43', '44',
                    '50', '51', '52', '53', '54',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'mcrypt' => [
            'constants'  => [
                '40',
                '1',
            ],
            'functions'  => [
                '40',
                '1',
            ],
            'iniEntries' => [
                '40',
                '1',
            ],
            'releases'   => [
                '40',
                '1',
            ],
        ];

        yield 'memcache' => [
            'classes'    => ['0', '3'],
            'constants'  => ['0', '2', '3'],
            'functions'  => ['0', '1', '2', '3'],
            'iniEntries' => ['2', '3', '4'],
            'methods'    => ['0', '3'],
            'releases'   => ['0', '1', '2', '3', '4', '8'],
        ];

        yield 'memcached' => [
            'classes'    => ['0', '2'],
            'const'      => ['2', '3'],
            'iniEntries' => ['2', '3'],
            'methods'    => ['0', '2'],
            'releases'   => ['0', '2', '3'],
        ];

        yield 'mhash' => [
            'constants'  => ['40'],
            'functions'  => ['40'],
            'releases'   => ['40'],
        ];

        yield 'mongo' => [
            'classes'    => ['0', '1'],
            'const'      => ['0', '1'],
            'constants'  => ['1'],
            'functions'  => ['1'],
            'iniEntries' => ['0'],
            'interfaces' => ['1'],
            'methods'    => ['0', '1'],
            'releases'   => ['0', '1'],
        ];

        yield 'mongodb' => [
            'classes'    => ['1', '2'],
            'const'      => ['1'],
            'constants'  => ['1'],
            'functions'  => ['1'],
            'iniEntries' => ['1'],
            'interfaces' => ['1'],
            'methods'    => ['1', '2'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'msgpack' => [
            'classes'    => ['0'],
            'const'      => ['0', '3'],
            'constants'  => ['2', '3'],
            'functions'  => ['0'],
            'iniEntries' => ['0', '3'],
            'methods'    => ['0'],
            'releases'   => ['0', '2', '3'],
        ];

        yield 'mssql' => [
            'constants'  => ['40'],
            'functions'  => ['40', '42', '43'],
            'iniEntries' => [
                '40', '42', '43',
                '51', '55',
            ],
            'releases'   => [
                '40', '42', '43',
                '51', '55',
            ],
        ];

        yield 'mysql' => [
            'constants'  => ['40', '43'],
            'functions'  => [
                '40', '43',
                '52',
            ],
            'iniEntries' => ['40', '43'],
            'releases'   => [
                '40', '43',
                '52',
            ],
        ];

        yield 'mysqli' => [
            'classes'    => ['50'],
            'constants'  => [
                '50', '51', '52', '53', '54', '55', '56',
                '72',
                '81', '84',
            ],
            'functions'  => [
                '50', '53', '54', '55', '56',
                '81', '82',
            ],
            'iniEntries' => [
                '50', '56',
                '81',
            ],
            'methods'    => [
                '50',
                '81', '82',
            ],
            'releases'   => array_merge(
                ['50', '51', '52', '53', '54', '55', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'oauth' => [
            'classes'    => ['0', '1'],
            'constants'  => ['0', '1'],
            'functions'  => ['0'],
            'methods'    => ['0', '1'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'oci8' => [
            'classes'    => ['1', '3'],
            'constants'  => ['1'],
            'functions'  => ['1', '2', '3'],
            'iniEntries' => ['1', '3'],
            'methods'    => ['1', '3'],
            'releases'   => array_merge(
                ['1', '2', '3'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'odbc' => [
            'classes'  => [
                '84',
            ],
            'constants'  => [
                '40', '43',
                '54',
            ],
            'functions'  => [
                '40',
                '82',
            ],
            'iniEntries' => [
                '40',
                '53',
            ],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '53', '54',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'opcache' => [
            'functions'  => ['70'],
            'iniEntries' => [
                '56',
                '70', '71', '74',
                '80', '83',
            ],
            'releases'   => array_merge(
                ['56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'openssl' => [
            'classes'    => ['80'],
            'constants'  => [
                '40', '43',
                '50', '52', '53', '54', '56',
                '71',
                '80', '83', '84',
            ],
            'functions'  => [
                '40', '42',
                '52', '53', '55', '56',
                '71', '72', '73', '74',
                '80', '82',
            ],
            'iniEntries' => ['56'],
            'releases'   => array_merge(
                [
                    '40', '42', '43',
                    '50', '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'opentelemetry' => [
            'functions' => ['1'],
            'iniEntries' => ['1'],
            'releases' => [
                '1',
            ],
        ];

        yield 'pcntl' => [
            'classes' => [
                '84',
            ],
            'const' => [
                '84',
            ],
            'constants' => [
                '41',
                '50', '53',
                '70', '72', '74',
                '84',
            ],
            'functions' => [
                '41', '42', '43',
                '50', '53',
                '70', '71', '74',
                '84',
            ],
            'releases'  => array_merge(
                [
                    '41', '42', '43',
                    '50', '53',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pcre' => [
            'constants'  => [
                '40', '43',
                '52',
                '70', '72', '73',
            ],
            'functions'  => [
                '40',
                '52', '53',
                '70',
                '80',
            ],
            'iniEntries' => [
                '52',
                '70',
            ],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '52', '53',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pdflib' => [
            'classes'   => ['2'],
            'functions' => ['2', '3'],
            'methods'   => ['2', '3'],
            'releases'  => ['1', '2', '3', '4'],
        ];

        yield 'pdo' => [
            'classes'   => ['51'],
            'const'     => [
                '51',
                '80', '81', '84',
            ],
            'functions' => ['51'],
            'methods'   => [
                '51',
                '84',
            ],
            'releases'  => array_merge(
                ['51'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pgsql' => [
            'classes'    => ['81'],
            'constants'  => [
                '40',
                '51', '54', '56',
                '71', '73',
                '83', '84',
            ],
            'functions'  => [
                '40', '42', '43',
                '50', '51', '52', '54', '56',
                '83', '84',
            ],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                [
                    '40', '42', '43',
                    '50', '51', '52', '54', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'phar' => [
            'classes'    => ['1', '2'],
            'const'      => [
                '1', '2',
                '81',
            ],
            'iniEntries' => ['1', '2'],
            'methods'    => ['1', '2'],
            'releases'   => array_merge(
                ['1', '2'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'posix' => [
            'constants'  => [
                '51',
                '70',
                '83', '84',
            ],
            'functions'  => [
                '40', '42',
                '51', '52',
                '70',
                '83',
            ],
            'releases'   => array_merge(
                [
                    '40', '42',
                    '51', '52',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pspell' => [
            'classes' => [
                '1',
            ],
            'constants' => [
                '1',
            ],
            'functions' => [
                '1',
            ],
            'releases'   => array_merge(
                self::PHP_RELEASES_7,
                ['80', '81', '82', '83'],
                ['1'],
            ),
        ];

        yield 'pthreads' => [
            'classes'   => ['0', '1', '2'],
            'constants' => ['0', '2'],
            'methods'   => ['0', '1', '2', '3'],
            'releases'  => ['0', '1', '2', '3'],
        ];

        yield 'random' => [
            'classes' => ['82', '83'],
            'const' => ['83'],
            'constants' => ['82'],
            'functions' => ['82'],
            'interfaces' => ['82'],
            'methods' => ['82', '83'],
            'releases' => [
                '82', '83', '84',
            ],
        ];

        yield 'raphf' => [
            'functions'  => ['2'],
            'iniEntries' => ['2'],
            'releases'   => ['2'],
        ];

        yield 'rar' => [
            'classes'   => ['2'],
            'const'     => ['0', '2', '4'],
            'constants' => ['2'],
            'functions' => ['2', '3'],
            'methods'   => ['0', '2', '3', '4'],
            'releases'  => ['0', '1', '2', '3', '4'],
        ];

        yield 'rdkafka' => [
            'classes' => ['1', '4'],
            'constants' => ['1', '5'],
            'functions' => ['1', '5'],
            'methods' => ['1', '3', '4', '5', '6'],
            'releases' => ['1', '2', '3', '4', '5', '6'],
        ];

        yield 'readline' => [
            'constants'  => ['54'],
            'functions'  => [
                '40',
                '51',
            ],
            'iniEntries' => ['54'],
            'releases'   => array_merge(
                [
                    '40',
                    '51', '54',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'recode' => [
            'functions' => ['40'],
            'releases'  => ['40'],
        ];

        yield 'redis' => [
            'classes'    => ['2', '5'],
            'const'      => ['2', '4', '5', '6'],
            'iniEntries' => ['2', '3', '4', '5', '6'],
            'methods'    => ['2', '3', '4', '5', '6'],
            'releases'   => ['2', '3', '4', '5', '6'],
        ];

        yield 'reflection' => [
            'classes'    => [
                '50', '54',
                '70', '71', '74',
                '80', '81', '84',
            ],
            'const'      => [
                '50', '51',
                '80', '81', '82', '84',
            ],
            'interfaces' => ['50'],
            'methods'    => [
                '50', '54',
                '70', '71', '72', '74',
                '80', '81', '82', '83', '84',
            ],
            'releases'   => array_merge(
                ['50', '51', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'riak' => [
            'classes'    => ['0', '1'],
            'iniEntries' => ['0', '1'],
            'interfaces' => ['0', '1'],
            'methods'    => ['0', '1'],
            'releases'   => ['0', '1'],
        ];

        yield 'session' => [
            'classes'    => ['54'],
            'constants'  => [
                '40',
                '54',
            ],
            'functions'  => [
                '40', '42', '43', '44',
                '54', '56',
                '71',
            ],
            'iniEntries' => [
                '40', '43',
                '50', '52', '54', '55',
                '70', '71', '73',
            ],
            'interfaces' => [
                '54', '55',
                '70',
            ],
            'methods'    => [
                '54', '55',
                '70',
            ],
            'releases'   => array_merge(
                [
                    '40', '42', '43', '44',
                    '50', '52', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'shmop' => [
            'classes'    => ['80'],
            'functions'  => ['40'],
            'releases'   => array_merge(
                ['40'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'simplexml' => [
            'classes'    => ['50', '51'],
            'functions'  => ['50'],
            'methods'    => ['50'],
            'releases'   => array_merge(
                ['50', '51'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'snmp' => [
            'classes'    => ['54'],
            'const'      => ['54'],
            'constants'  => [
                '43',
                '52', '54',
            ],
            'functions'  => [
                '40', '43',
                '50', '52',
            ],
            'methods'    => ['54'],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '50', '52', '54',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'soap' => [
            'classes'    => [
                '50',
                '84',
            ],
            'constants'  => ['50', '55'],
            'functions'  => ['50'],
            'iniEntries' => ['50'],
            'methods'    => [
                '50',
                '80', '84',
            ],
            'releases'   => array_merge(
                ['50', '55'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'sockets' => [
            'classes'    => ['80'],
            'constants'  => [
                '41', '43',
                '52', '54', '55',
                '70', '72',
                '81', '82', '83', '84',
            ],
            'functions'  => [
                '41', '42', '43',
                '54', '55',
                '70', '72',
                '83',
            ],
            'releases'   => array_merge(
                ['41', '42', '43', '52', '54', '55'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'solr' => [
            'classes'   => ['0', '1', '2'],
            'const'     => ['0', '2'],
            'constants' => ['0'],
            'functions' => ['0'],
            'methods'   => ['0', '1', '2'],
            'releases'  => ['0', '1', '2'],
        ];

        yield 'sphinx' => [
            'classes'   => ['0'],
            'constants' => ['0', '1'],
            'methods'   => ['0', '1'],
            'releases'  => ['0', '1'],
        ];

        yield 'spl' => [
            'classes'    => ['50', '51', '52', '53', '54'],
            'const'      => ['50', '51', '52', '53'],
            'functions'  => ['50', '51', '52', '54', '72'],
            'interfaces' => ['51'],
            'methods'    => [
                '50', '51', '52', '53', '54',
                '70',
                '82',
            ],
            'releases'   => array_merge(
                ['50', '51', '52', '53', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'sqlite' => [
            'classes'    => ['2'],
            'constants'  => ['2'],
            'functions'  => ['2'],
            'iniEntries' => ['2'],
            'releases'   => ['2'],
        ];

        yield 'sqlite3' => [
            'classes'    => [
                '53',
                '83',
            ],
            'const'      => ['80'],
            'constants'  => [
                '53',
                '71',
            ],
            'iniEntries' => ['53', '56'],
            'methods'    => [
                '53',
                '74',
                '80',
            ],
            'releases'   => array_merge(
                ['53', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ssh2' => [
            'constants' => ['0'],
            'functions' => ['0', '1'],
            'releases'  => ['0', '1'],
        ];

        yield 'standard' => [
            'classes' => [
                '40',
                '50',
                '70',
                '84',
            ],
            'const' => [
                '84',
            ],
            'constants' => [
                '40', '41', '43',
                '51', '52', '53', '54', '55', '56',
                '70', '71', '72', '73', '74',
                '80', '81', '84',
            ],
            'functions' => [
                '40', '41', '42', '43',
                '50', '51', '52', '53', '54', '55',
                '70', '71', '72', '73', '74',
                '80', '81', '82', '83', '84',
            ],
            'iniEntries' => [
                '40',
                '53',
                '70', '71', '74'
            ],
            'methods' => [
                '40',
                '50',
            ],
            'releases' => array_merge(
                [
                    '40', '41', '42', '43', '44',
                    '50', '51', '52', '53', '54', '55', '56',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'stomp' => [
            'classes'    => ['0'],
            'functions'  => ['0', '1'],
            'iniEntries' => ['0', '1'],
            'methods'    => ['0', '1'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'svn' => [
            'classes'   => ['0'],
            'const'     => ['1'],
            'constants' => ['0', '1'],
            'functions' => ['0', '1'],
            'methods'   => ['1'],
            'releases'  => ['0', '1', '2'],
        ];

        yield 'sync' => [
            'classes'  => ['1'],
            'methods'  => ['1'],
            'releases' => ['1'],
        ];

        yield 'sysvmsg' => [
            'classes'   => ['80'],
            'constants' => [
                '43',
                '52',
            ],
            'functions' => [
                '43',
                '53',
            ],
            'releases'  => array_merge(
                [
                    '43',
                    '52', '53',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'sysvsem' => [
            'classes'   => ['80'],
            'functions' => ['40', '41'],
            'releases'  => array_merge(
                [
                    '40', '41'
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'sysvshm' => [
            'classes'   => ['80'],
            'functions' => [
                '40',
                '53',
            ],
            'releases'  => array_merge(
                [
                    '40',
                    '53',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'tidy' => [
            'classes'    => ['0'],
            'constants'  => [
                '0',
                '74',
            ],
            'functions'  => [
                '0',
                '50', '51',
            ],
            'iniEntries' => ['0'],
            'methods'    => [
                '0',
                '84',
            ],
            'releases'   => array_merge(
                [
                    '0', '1',
                    '50', '51',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'tokenizer' => [
            'classes'   => ['80'],
            'constants' => [
                '42', '43',
                '50', '51', '53', '54', '55', '56',
                '70', '74',
                '80', '81', '84',
            ],
            'functions' => ['42'],
            'methods'   => ['80'],
            'releases'  => array_merge(
                [
                    '42', '43',
                    '50', '51', '53', '54', '55', '56'
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'uopz' => [
            'constants'  => ['2'],
            'functions'  => ['2', '5'],
            'iniEntries' => ['2', '5', '6'],
            'releases'   => ['2', '5', '6'],
        ];

        yield 'uploadprogress' => [
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'uuid' => [
            'constants' => ['1'],
            'functions' => ['1'],
            'releases'  => ['1'],
        ];

        yield 'varnish' => [
            'classes'   => ['0'],
            'const'     => ['0', '6'],
            'constants' => ['0'],
            'methods'   => ['0', '1'],
            'releases'  => ['0', '1', '6'],
        ];

        yield 'wddx' => [
            'functions' => ['40'],
            'releases'  => ['40'],
        ];

        yield 'xcache' => [
            'constants'  => ['1', '2'],
            'functions'  => ['1', '2'],
            'iniEntries' => ['1', '2', '3'],
            'releases'   => ['1', '2', '3'],
        ];

        yield 'xdebug' => [
            'constants'  => ['2', '3'],
            'functions'  => ['1', '2', '3'],
            'iniEntries' => ['1', '2', '3'],
            'releases'   => ['1', '2', '3'],
        ];

        yield 'xhprof' => [
            'constants'  => ['0'],
            'functions'  => ['0'],
            'iniEntries' => ['0', '2'],
            'releases'   => ['0', '2'],
        ];

        yield 'xlswriter' => [
            'classes'   => ['1'],
            'const'     => ['1'],
            'functions' => ['1'],
            'methods'   => ['1'],
            'releases'  => ['1'],
        ];

        yield 'xml' => [
            'classes'   => ['80'],
            'constants' => [
                '40',
                '84',
            ],
            'functions' => ['40'],
            'releases'  => array_merge(
                ['40'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'xmldiff' => [
            'classes'  => ['0'],
            'methods'  => ['0'],
            'releases' => ['0', '1'],
        ];

        yield 'xmlreader' => [
            'classes'   => ['50'],
            'const'     => ['50'],
            'methods'   => [
                '50',
                '84',
            ],
            'releases'  => array_merge(
                ['50'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'xmlrpc' => [
            'classes'   => ['1'],
            'functions' => [
                '41',
                '1',
            ],
            'releases'  => [
                '41', '43',
                '1',
            ],
        ];

        yield 'xmlwriter' => [
            'classes'   => ['51'],
            'functions' => ['51', '52'],
            'methods'   => [
                '51',
                '84',
            ],
            'releases'  => array_merge(
                ['51', '52'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'xpass' => [
            'constants' => ['1'],
            'functions' => ['1'],
            'releases'  => ['1'],
        ];

        yield 'xsl' => [
            'classes'    => ['50'],
            'constants'  => ['50', '51', '53'],
            'methods'    => [
                '50',
                '84',
            ],
            'iniEntries' => ['53'],
            'releases'   => array_merge(
                ['50', '51', '53'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'yac' => [
            'classes'    => ['0'],
            'constants'  => ['0', '2'],
            'iniEntries' => ['0', '2'],
            'methods'    => ['0', '2'],
            'releases'   => ['0', '2'],
        ];

        yield 'yaml' => [
            'constants'  => ['0'],
            'functions'  => ['0'],
            'iniEntries' => ['0', '1'],
            'releases'   => ['0', '1', '2'],
        ];

        yield 'zip' => [
            'classes'   => ['1'],
            'const'     => ['1'],
            'functions' => ['1'],
            'methods'   => ['1'],
            'releases'  => ['1'],
        ];

        yield 'zlib' => [
            'classes'    => ['80'],
            'constants'  => [
                '40',
                '54',
                '70',
            ],
            'functions'  => [
                '40', '43',
                '54',
                '70', '72',
            ],
            'iniEntries' => ['40', '43'],
            'releases'   => array_merge(
                [
                    '40', '43',
                    '54',
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];
    }
}
