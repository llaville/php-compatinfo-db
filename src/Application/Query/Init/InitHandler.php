<?php declare(strict_types=1);

/**
 * Handler to initialize the database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Init;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Console\Helper\ProgressBar;

use FilesystemIterator;
use Generator;
use RecursiveDirectoryIterator;
use RuntimeException;
use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function dirname;
use function implode;
use function json_last_error;
use function sprintf;
use const DIRECTORY_SEPARATOR;
use const JSON_ERROR_NONE;

/**
 * @since Release 2.0.0RC1
 */
final class InitHandler implements QueryHandlerInterface
{
    private const PHP_RELEASES_7 = ['70', '71', '72', '73', '74'];
    private const PHP_RELEASES_8 = ['80', '81'];

    /** @var JsonFileHandler */
    private $jsonFileHandler;

    /** @var DistributionRepository */
    private $distributionRepository;

    public function __construct(
        JsonFileHandler $jsonFileHandler,
        DistributionRepository $distributionRepository
    ) {
        $this->jsonFileHandler = $jsonFileHandler;
        $this->distributionRepository = $distributionRepository;
    }

    /**
     * @param InitQuery $query
     * @return int
     */
    public function __invoke(InitQuery $query): int
    {
        $distVersion = $query->getAppVersion();
        $platform = $this->distributionRepository->getDistributionByVersion($distVersion);
        if (null !== $platform) {
            if (!$query->isForce()) {
                return 1;
            }
            $this->distributionRepository->clear();
        }

        $refDir = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'data', 'reference', 'extension']);
        $flags = FilesystemIterator::SKIP_DOTS;
        $refs = new RecursiveDirectoryIterator($refDir, $flags);

        $extensions = [];

        foreach ($refs as $ref) {
            $extensions[$ref->getBasename()] = $ref->getPathname();
        }
        unset($refs);

        $io = $query->getStyle();

        $withProgressBar = $query->isProgress();

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
            if ($withProgressBar) {
                $progress->setMessage(sprintf("Building %s (%s)", $component, $refName));
                $progress->display();
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

        if ($withProgressBar) {
            $progress->setMessage('Flushing all changes to the database ...');
            $progress->display();
        }

        $platform = $this->distributionRepository->initialize($collection, $distVersion);

        if ($withProgressBar) {
            $progress->setMessage('');
            $progress->display();
            $progress->finish();
        }

        if ($io->isDebug()) {
            $io->section('CompatInfoDb platform(s)');
            $io->text((string) $platform);

            $io->section('CompatInfoDb extension(s)');
            $io->text(
                array_map(
                    function($item) {
                        return (string) $item;
                    },
                    $platform->getExtensions()
                )
            );
        }

        if (count($extensions) > 0) {
            $io->section('Warnings');
            $io->text('Definition not provided for following references:');
            $io->text('');
            $io->listing(array_keys($extensions), ['type' => '[ ]', 'style' => 'fg=red']);
        }

        return 0;
    }

    /**
     * Builds an extension with all its components.
     *
     * @param array<string, string> $meta
     * @param array<string, array> $definition
     * @param string $refPathname
     * @return array<string, array>
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
     * @param string $path
     * @param string[] $majorReleases
     * @param string $fileBasename
     * @param string $type
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
     * @return Generator<string, array>
     */
    private function majorReleaseDefinitionProvider(): Generator
    {
        yield 'amqp' => [
            'classes'    => ['0', '1'],
            'const'      => ['1'],
            'constants'  => ['1'],
            'iniEntries' => ['0', '1'],
            'methods'    => ['0', '1'],
            'releases'   => ['0', '1'],
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
            'functions'  => ['40', '50'],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                ['40', '50'],
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
            'constants' => ['40', '43', '50'],
            'functions' => ['40'],
            'releases'  => array_merge(
                ['40', '43', '50'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'core' => [
            'classes' => [
                '40',
                '50', '51', '53', '55',
                '70', '71', '73', '74',
                '80', '81',
            ],
            'const' => ['80'],
            'constants' => [
                '40', '42', '43', '44',
                '50', '51', '52', '53', '54',
                '71'
            ],
            'functions' => [
                '40', '42', '43',
                '50', '51', '53', '54',
                '70', '73', '74',
                '80', '81',
            ],
            'methods' => [
                '51', '53', '55',
                '70', '72', '74',
                '80', '81',
            ],
            'iniEntries' => [
                '40', '41',
                '50', '54', '55', '56',
                '70', '71', '73', '74',
                '80', '81'
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
            'classes'    => ['55', '80', '81'],
            'constants'  => ['40', '51', '52', '53', '54', '55', '56', '70', '73', '81'],
            'functions'  => ['40', '50', '51', '55', '71'],
            'iniEntries' => ['53'],
            'methods'    => ['55', '81'],
            'releases'   => array_merge(
                ['40', '51', '52', '53', '54', '55', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'date' => [
            'classes'    => ['52', '53', '55'],
            'const'      => ['52', '53', '70', '71', '72'],
            'constants'  => ['51', '70'],
            'functions'  => ['40', '50', '51', '52', '53', '55'],
            'iniEntries' => ['51'],
            'interfaces' => ['55'],
            'methods'    => ['52', '53', '55', '56', '72', '73', '80'],
            'releases'   => array_merge(
                ['40', '50', '51', '52', '53', '55'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'dom' => [
            'classes'    => ['50', '51'],
            'constants'  => ['50'],
            'functions'  => ['50'],
            'interfaces' => ['80'],
            'methods'    => ['50', '80'],
            'releases'   => array_merge(
                ['50', '51'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'enchant' => [
            'classes'    => ['80'],
            'constants'  => ['1', '80'],
            'functions'  => ['0', '1', '80'],
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
            'constants'  => ['0', '1', '72'],
            'functions'  => ['0', '1'],
            'methods'    => ['0', '80'],
            'releases'   => array_merge(
                ['0', '1'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'filter' => [
            'constants'  => ['0', '70', '71', '73', '80'],
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
            'constants'  => ['40', '43', '56'],
            'functions'  => ['40', '42', '43', '50', '72'],
            'releases'   => array_merge(
                ['40', '42', '43', '50', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'gd' => [
            'classes'    => ['80', '81'],
            'constants'  => ['40', '52', '53', '55', '56', '72', '74', '81'],
            'functions'  => ['40', '43', '50', '51', '52', '54', '55', '72', '74', '80'],
            'iniEntries' => ['51'],
            'releases'   => array_merge(
                ['40', '43', '50', '51', '52', '53', '54', '55', '56'],
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
            'constants'  => ['40', '53', '56'],
            'functions'  => ['40', '52', '53', '56', '70', '73'],
            'releases'   => array_merge(
                ['40', '52', '53', '56'],
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
            'constants'  => ['1', '53', '54', '81'],
            'functions'  => ['1', '53', '55', '56', '71', '72'],
            'iniEntries' => [],
            'methods'    => ['72'],
            'releases'   => array_merge(
                ['1', '53', '54', '55', '56'],
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
            'methods'    => ['2', '3'],
            'releases'   => ['0', '1', '2', '3', '4'],
        ];

        yield 'iconv' => [
            'constants'  => ['43', '50'],
            'functions'  => ['40', '50'],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                ['40', '43', '50'],
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
            'constants'  => ['40', '53'],
            'functions'  => ['40', '43', '50', '51', '53'],
            'iniEntries' => ['56'],
            'releases'   => array_merge(
                ['40', '43', '50', '51', '53', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'inclued' => [
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'releases'   => ['0'],
        ];

        yield 'intl' => [
            'classes'    => ['1', '2', '55', '70'],
            'const'      => ['1', '2', '55', '70'],
            'constants'  => ['1', '2'],
            'functions'  => ['1', '2', '55', '71', '73'],
            'iniEntries' => ['1', '3'],
            'methods'    => ['1', '2', '55', '70', '71', '73'],
            'releases'   => array_merge(
                ['1', '2', '3', '55'],
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
            'constants'  => ['53', '54', '55', '56', '70', '71', '72', '73'],
            'functions'  => ['52', '53', '55'],
            'interfaces' => ['54'],
            'methods'    => ['54'],
            'releases'   => array_merge(
                ['52', '53', '54', '55', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'ldap' => [
            'classes'    => [],
            'constants'  => ['40', '53', '54', '56', '70', '71', '72'],
            'functions'  => ['40', '42', '50', '54', '56', '72', '73', '80'],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                ['40', '42', '50', '53', '54', '56'],
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
            'constants'  => ['51', '52', '53', '54', '55', '70'],
            'functions'  => ['50', '51', '52', '54'],
            'releases'   => array_merge(
                ['51', '52', '53', '54', '55'],
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
            'constants'  => ['40', '73', '74'],
            'functions'  => ['40', '42', '43', '44', '50', '52', '53', '54', '72', '74'],
            'iniEntries' => ['40', '42', '43', '51', '73', '74'],
            'releases'   => array_merge(
                ['40', '42', '43', '44', '50', '51', '52', '53', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'mcrypt' => [
            'constants'  => ['40', '1'],
            'functions'  => ['40', '1'],
            'iniEntries' => ['40', '1'],
            'releases'   => ['40', '1'],
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

        yield 'msgpack' => [
            'classes'    => ['0'],
            'const'      => ['0'],
            'constants'  => ['2'],
            'functions'  => ['0'],
            'iniEntries' => ['0'],
            'methods'    => ['0'],
            'releases'   => ['0', '2'],
        ];

        yield 'mssql' => [
            'constants'  => ['40'],
            'functions'  => ['40', '42', '43'],
            'iniEntries' => ['40', '42', '43', '51', '55'],
            'releases'   => ['40', '42', '43', '51', '55'],
        ];

        yield 'mysql' => [
            'constants'  => ['40', '43'],
            'functions'  => ['40', '43', '52'],
            'iniEntries' => ['40', '43'],
            'releases'   => ['40', '43', '52'],
        ];

        yield 'mysqli' => [
            'classes'    => ['50'],
            'constants'  => ['50', '51', '52', '53', '54', '55', '56', '72'],
            'functions'  => ['50', '53', '54', '55', '56'],
            'iniEntries' => ['50', '56'],
            'methods'    => ['50'],
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
            'functions'  => ['1', '2'],
            'iniEntries' => ['1'],
            'methods'    => ['1', '3'],
            'releases'   => array_merge(
                ['1', '2', '3'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'odbc' => [
            'constants'  => ['40', '43', '54'],
            'functions'  => ['40'],
            'iniEntries' => ['40', '53'],
            'releases'   => array_merge(
                ['40', '43', '53', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'opcache' => [
            'functions'  => ['70'],
            'iniEntries' => ['56', '70', '71', '74', '80'],
            'releases'   => array_merge(
                ['56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'openssl' => [
            'classes'    => ['80'],
            'constants'  => ['40', '43', '50', '52', '53', '54', '56', '71', '80'],
            'functions'  => ['40', '42', '52', '53', '55', '56', '71', '72', '73', '74', '80'],
            'iniEntries' => ['56'],
            'releases'   => array_merge(
                ['40', '42', '43', '50', '52', '53', '54', '55', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pcntl' => [
            'constants' => ['41', '50', '53', '70', '72', '74'],
            'functions' => ['41', '42', '43', '50', '53', '70', '71', '74'],
            'releases'  => array_merge(
                ['41', '42', '43', '50', '53'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pcre' => [
            'constants'  => ['40', '43', '52', '70', '72', '73'],
            'functions'  => ['40', '52', '53', '70', '80'],
            'iniEntries' => ['52', '70'],
            'releases'   => array_merge(
                ['40', '43', '52', '53'],
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
            'const'     => ['51', '80'],
            'functions' => ['51'],
            'methods'   => ['51'],
            'releases'  => array_merge(
                ['51'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pgsql' => [
            'constants'  => ['40', '51', '54', '56', '71', '73'],
            'functions'  => ['40', '42', '43', '50', '51', '52', '54', '56'],
            'iniEntries' => ['40'],
            'releases'   => array_merge(
                ['40', '42', '43', '50', '51', '52', '54', '56'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'phar' => [
            'classes'    => ['1', '2'],
            'const'      => ['1', '2'],
            'iniEntries' => ['1', '2'],
            'methods'    => ['1', '2'],
            'releases'   => array_merge(
                ['1', '2'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'posix' => [
            'constants'  => ['51', '70'],
            'functions'  => ['40', '42', '51', '52', '70'],
            'releases'   => array_merge(
                ['40', '42', '51', '52'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'pthreads' => [
            'classes'   => ['0', '1', '2'],
            'constants' => ['0', '2'],
            'methods'   => ['0', '1', '2', '3'],
            'releases'  => ['0', '1', '2', '3'],
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
            'constants' => ['1'],
            'functions' => ['1'],
            'methods' => ['1', '3', '4'],
            'releases' => ['1', '2', '3', '4', '5'],
        ];

        yield 'readline' => [
            'constants'  => ['54'],
            'functions'  => ['40', '51'],
            'iniEntries' => ['54'],
            'releases'   => array_merge(
                ['40', '51', '54'],
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
            'const'      => ['2', '4', '5'],
            'iniEntries' => ['2', '3', '4', '5'],
            'methods'    => ['2', '3', '4', '5'],
            'releases'   => ['2', '3', '4', '5'],
        ];

        yield 'reflection' => [
            'classes'    => ['50', '54', '70', '71', '74', '80'],
            'const'      => ['50', '51'],
            'interfaces' => ['50'],
            'methods'    => ['50', '54', '70', '71', '72', '74', '80'],
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
            'constants'  => ['40', '54'],
            'functions'  => ['40', '42', '43', '44', '54', '56', '71'],
            'iniEntries' => ['40', '43', '50', '52', '54', '55', '70', '71', '73'],
            'interfaces' => ['54', '55', '70'],
            'methods'    => ['54', '55', '70'],
            'releases'   => array_merge(
                ['40', '42', '43', '44', '50', '52', '54', '55', '56'],
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
            'constants'  => ['43', '52', '54'],
            'functions'  => ['40', '43', '50', '52'],
            'methods'    => ['54'],
            'releases'   => array_merge(
                ['40', '43', '50', '52', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'soap' => [
            'classes'    => ['50'],
            'constants'  => ['50', '55'],
            'functions'  => ['50'],
            'iniEntries' => ['50'],
            'methods'    => ['50', '80'],
            'releases'   => array_merge(
                ['50', '55'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'sockets' => [
            'classes'    => ['80'],
            'constants'  => ['41', '43', '52', '54', '55', '70', '72'],
            'functions'  => ['41', '42', '43', '54', '55', '70', '72'],
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
            'methods'    => ['50', '51', '52', '53', '54', '70'],
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
            'classes'    => ['53'],
            'const'      => ['80'],
            'constants'  => ['53', '71'],
            'iniEntries' => ['53', '56'],
            'methods'    => ['53', '74', '80'],
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
                '70'
            ],
            'constants' => [
                '40', '41', '43',
                '51', '52', '53', '54', '55', '56',
                '70', '71', '72', '73', '74',
                '80'
            ],
            'functions' => [
                '40', '41', '42', '43',
                '50', '51', '52', '53', '54', '55',
                '70', '71', '72', '73', '74',
                '80'
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
            'constants' => ['43', '52'],
            'functions' => ['43', '53'],
            'releases'  => array_merge(
                [
                    '43', '52', '53'
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
            'functions' => ['40', '53'],
            'releases'  => array_merge(
                [
                    '40', '53'
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'tidy' => [
            'classes'    => ['0'],
            'constants'  => ['0', '74'],
            'functions'  => ['0', '50', '51'],
            'iniEntries' => ['0'],
            'methods'    => ['0'],
            'releases'   => array_merge(
                [
                    '0', '1', '50', '51'
                ],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'tokenizer' => [
            'classes'   => ['80'],
            'constants' => ['42', '43', '50', '51', '53', '54', '55', '56', '70', '74', '80'],
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
            'releases'   => ['0', '1'],
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

        yield 'xml' => [
            'classes'   => ['80'],
            'constants' => ['40'],
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
            'methods'   => ['50'],
            'releases'  => array_merge(
                ['50'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'xmlrpc' => [
            'classes'   => ['1'],
            'functions' => ['41', '1'],
            'releases'  => ['41', '43', '1'],
        ];

        yield 'xmlwriter' => [
            'classes'   => ['51'],
            'functions' => ['51', '52'],
            'methods'   => ['51'],
            'releases'  => array_merge(
                ['51', '52'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];

        yield 'xsl' => [
            'classes'    => ['50'],
            'constants'  => ['50', '51', '53'],
            'methods'    => ['50'],
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
            'constants'  => ['40', '54', '70'],
            'functions'  => ['40', '43', '54', '70', '72'],
            'iniEntries' => ['40', '43'],
            'releases'   => array_merge(
                ['40', '43', '54'],
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8
            ),
        ];
    }
}
