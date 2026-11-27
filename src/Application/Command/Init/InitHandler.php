<?php

declare(strict_types=1);

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

use Boundwize\JsonRecast\Node\ArrayItemNode;
use Boundwize\JsonRecast\Node\ArrayNode;
use Boundwize\JsonRecast\Node\ObjectNode;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Helper\ProgressBar;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Throwable;

use function array_map;
use function array_merge;
use function array_push;
use function basename;
use function count;
use function dirname;
use function end;
use function explode;
use function implode;
use function in_array;
use function ksort;
use function sort;
use function sprintf;
use function str_contains;
use function str_replace;

use const DIRECTORY_SEPARATOR;

/**
 * Handler to initialize the database.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class InitHandler implements CommandHandlerInterface
{
    private const int RETURN_CODE_DISTRIBUTION_PLATFORM_EXISTS = 110;
    private const int RETURN_CODE_DATABASE_READONLY = 120;
    private const array PHP_RELEASES_7 = ['70', '71', '72', '73', '74'];
    private const array PHP_RELEASES_8 = ['80', '81', '82', '83', '84', '85'];

    public function __construct(
        private readonly JsonFileHandler $jsonFileHandler,
        private readonly DistributionRepository $distributionRepository,
        private readonly EntityManagerInterface $entityManager,
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
                        self::RETURN_CODE_DISTRIBUTION_PLATFORM_EXISTS,
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
        $iterator = new RecursiveIteratorIterator($refs);
        $extensions = [];

        foreach ($iterator as $key => $info) {
            [, $refName] = explode(DIRECTORY_SEPARATOR, str_replace($refDir, '', $key));
            $extensions[$refName][] = $key;
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

        ksort($extensions);
        foreach ($extensions as $refName => $files) {
            $message = sprintf("Building %s extension", $refName);
            if ($withProgressBar) {
                $progress->setMessage($message);
                $progress->display();
            } elseif ($io->isVerbose()) {
                $io->writeln('> ' . $message . ' ...');
            }

            sort($files);

            // READ extension metadata
            $filename = end($files);

            $document = $this->jsonFileHandler->parse($filename);

            /** @var ObjectNode $node */
            $node = $document->value;

            $refType = $node->get('type')->value->value;
            $refDeprecated = $node->get('deprecated') ? $node->get('deprecated')->value->value : false;

            $releases = $this->useCoreReleases($refName);
            $fileReleases = count($releases) === 0 ? [] : array_map(static function ($release) use ($refDir) {
                return implode(DIRECTORY_SEPARATOR, [$refDir, 'core', $release, 'releases.json']);
            }, $releases);

            if (count($fileReleases)) {
                array_push($files, ...$fileReleases);
            }

            $collection->add($this->buildExtension($refName, $refType, $refDeprecated, $files, $io));

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
                    static function ($item) {
                        return (string) $item;
                    },
                    $distribution->getExtensions(),
                ),
            );

            $io->section('Release(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        static function ($item) {
                            return (string) $item;
                        },
                        $extension->getReleases()->toArray(),
                    ),
                );
            }

            $io->section('Configuration(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        static function ($item) {
                            return (string) $item;
                        },
                        $extension->getIniEntries()->toArray(),
                    ),
                );
            }

            $io->section('Constant(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        static function ($item) {
                            return (string) $item;
                        },
                        $extension->getConstants()->toArray(),
                    ),
                );
            }

            $io->section('Function(s) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        static function ($item) {
                            return (string) $item;
                        },
                        $extension->getFunctions()->toArray(),
                    ),
                );
            }

            $io->section('Class(es) referenced');
            foreach ($distribution->getExtensions() as $extension) {
                $io->text(
                    array_map(
                        static function ($item) {
                            return (string) $item;
                        },
                        $extension->getClasses()->toArray(),
                    ),
                );
            }
        }
    }

    /**
     * Builds an extension with all its components.
     *
     * @return array<string, mixed>
     */
    private function buildExtension(string $refName, string $refType, bool $refDeprecated, array $files, StyleInterface $io): array
    {
        $data = [
            'name' => $refName,
            'type' => $refType,
            'deprecated' => $refDeprecated,
            'releases' => [],
            'iniEntries' => [],
            'constants' => [],
            'functions' => [],
            'classes' => [],
            'interfaces' => [],
            'const' => [],
            'methods' => [],
        ];

        foreach ($files as $file) {
            $document = $this->jsonFileHandler->parse($file);

            $component = basename($file, '.json');

            if ($component === 'releases') {
                /** @var ArrayNode $node */
                $node = $document->value;
                foreach ($node->items as $itemNode) {
                    /** @var ArrayItemNode $itemNode */
                    /** @var ObjectNode $nodeValue */
                    $nodeValue = $itemNode->value;
                    $data[$component][] = [
                        'rel_version' => $nodeValue->get('rel_version')->value->value,
                        'rel_date' => $nodeValue->get('rel_date')->value->value,
                        'rel_state' => $nodeValue->get('rel_state')->value->value,
                        'php_min' => $nodeValue->get('php_min')->value->value,
                    ];
                }
            }

            if (in_array($component, ['iniEntries', 'constants', 'functions', 'classes', 'interfaces', 'const', 'methods'])) {
                /** @var ArrayNode $node */
                $node = $document->value;
                foreach ($node->items as $itemNode) {
                    /** @var ArrayItemNode $itemNode */
                    /** @var ObjectNode $nodeValue */
                    $nodeValue = $itemNode->value;

                    $settings = [
                        'name' => $nodeValue->get('name')->value->value,
                        'ext_min' => $nodeValue->get('ext_min')->value->value,
                        'php_min' => $nodeValue->get('php_min')->value->value,
                    ];

                    $extMax = $nodeValue->get('ext_max');
                    if ($extMax) {
                        $settings['ext_max'] = $extMax->value->value;
                    }

                    $phpMax = $nodeValue->get('php_max');
                    if ($phpMax) {
                        $settings['php_max'] = $phpMax->value->value;
                    }

                    $polyfill = $nodeValue->get('polyfill');
                    if ($polyfill) {
                        $settings['polyfill'] = $polyfill->value->value;
                    }

                    $className = $nodeValue->get('class_name');
                    if ($className) {
                        $settings['class_name'] = $className->value->value;
                    }

                    $staticCall = $nodeValue->get('static');
                    if ($staticCall) {
                        $settings['static'] = $staticCall->value->value;
                    }

                    $dependencies = $nodeValue->get('dependencies');
                    if ($dependencies) {
                        $settings['dependencies'] = $this->getDependencies($dependencies->value);
                    }

                    $data[$component][] = $settings;
                }
            }
        }

        return $data;
    }

    private function getDependencies(ArrayNode $dependencies): array
    {
        $deps = [];

        foreach ($dependencies->items as $dependency) {
            if ($dependency->value instanceof ObjectNode) {
                $deps[] = [
                    'name' => $dependency->value->get('name')->value->value,
                    'constraint' => $dependency->value->get('constraint')->value->value,
                ];
            }
        }

        return $deps;
    }

    private function useCoreReleases(string $refName): array
    {
        $releases = match ($refName) {
            'psell' => array_merge(
                self::PHP_RELEASES_7,
                ['80', '81', '82', '83'],
            ),
            'random' => ['82', '83', '84', '85'],
            default => array_merge(
                self::PHP_RELEASES_7,
                self::PHP_RELEASES_8,
            ),
        };

        return match ($refName) {
            'bcmath', 'bz2',
            'calendar', 'core', 'ctype', 'curl',
            'date', 'dom',
            'enchant', 'exif',
            'fileinfo', 'filter', 'ftp',
            'gd', 'gettext', 'gmp',
            'hash',
            'iconv', 'imap', 'intl',
            'json',
            'ldap', 'libxml',
            'mbstring', 'mysqli',
            'oci8', 'odbc', 'opcache', 'openssl',
            'pcntl', 'pcre', 'pdo', 'pgsql', 'phar', 'posix', 'pspell',
            'random', 'readline', 'reflection',
            'session', 'shmop', 'simplexml', 'snmp', 'soap', 'sockets', 'spl', 'sqlite3', 'standard', 'sysvmsg', 'sysvsem', 'sysvshm',
            'tidy', 'tokenizer',
            'xml', 'xmlreader', 'xmlwriter', 'xsl',
            'zlib' => $releases,
            default => [],
        };
    }
}
