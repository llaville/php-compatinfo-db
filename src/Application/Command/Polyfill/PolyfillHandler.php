<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Command\Polyfill;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

use Exception;
use FilesystemIterator;
use LogicException;
use PharData;
use RecursiveDirectoryIterator;
use RuntimeException;
use function basename;
use function count;
use function dirname;
use function fclose;
use function file_exists;
use function file_get_contents;
use function fopen;
use function fwrite;
use function getenv;
use function implode;
use function json_decode;
use function mkdir;
use function preg_match;
use function sprintf;
use function str_replace;
use function sys_get_temp_dir;
use const DIRECTORY_SEPARATOR;

/**
 * Handler to add a new Polyfill in JSON files.
 *
 * @since Release 4.2.0
 * @author Laurent Laville
 */
final class PolyfillHandler implements CommandHandlerInterface
{
    private JsonFileHandler $jsonFileHandler;
    private string $composerBin;
    private string $compatInfoBin;

    public function __construct(JsonFileHandler $jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(PolyfillCommand $command): void
    {
        $this->composerBin = $this->getExecutable('composer');
        $this->compatInfoBin = $this->getExecutable('phpcompatinfo');

        $jsonResult = $this->getInfo($command->getPackage(), $command->getTag());
        if (empty($jsonResult)) {
            return;
        }

        $io = $command->getStyle();

        $targetDir = getenv('APP_CACHE_DIR') . DIRECTORY_SEPARATOR . $jsonResult['name'];

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
            if ($io->isVerbose()) {
                $io->writeln(sprintf('> Create <info>%s</info> target dir', $targetDir));
            }
        }

        $tempArchive = $targetDir
            . DIRECTORY_SEPARATOR
            . sprintf('%s.%s', $jsonResult['dist']['reference'], $jsonResult['dist']['type'])
        ;

        if (!file_exists($tempArchive)) {
            if (!$this->download($jsonResult['dist']['url'], $tempArchive)) {
                return;
            }
            if ($io->isVerbose()) {
                $io->writeln(sprintf('> Archive <info>%s</info> downloaded', $jsonResult['dist']['url']));
            }
        }

        $tempPackageDir = $this->extract($tempArchive);
        if (empty($tempPackageDir)) {
            return;
        }
        if ($io->isVerbose()) {
            $io->writeln(
                sprintf(
                    '> Archive <info>%s</info> extracted to <info>%s</info>',
                    basename($tempArchive),
                    $tempPackageDir
                )
            );
        }

        $results = $this->analyse($tempPackageDir);
        if (empty($results)) {
            return;
        }
        if ($io->isVerbose()) {
            $io->writeln(
                sprintf(
                    '> Polyfill analysed. Found <info>%d</info> constant(s) and <info>%d</info> function(s)',
                    count($results['constants']),
                    count($results['functions'])
                )
            );
        }

        $major = str_replace('.', '', $command->getPhp());
        if (!$this->updateReference($major, $results, $command->getPackage())) {
            throw new LogicException(
                'No reference updated. <comment>Verify if "php" option is accordingly set to this polyfill.</comment>'
            );
        }
    }

    private function getExecutable(string $name): string
    {
        $executable = (new ExecutableFinder())->find($name, null, [getenv('APP_VENDOR_DIR') . DIRECTORY_SEPARATOR . 'bin']);
        if (!$executable) {
            throw new RuntimeException(sprintf('Unable to find "%s" executable.', $name));
        }
        return $executable;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getInfo(string $packageName, string $packageTag): ?array
    {
        $process = new Process([$this->composerBin, 'show', '--all', '--format=json' , $packageName, $packageTag]);
        $process->start();
        while ($process->isRunning()) {
            // waiting for process to finish
        }

        $composerStatus = $process->getExitCode();
        if ($composerStatus > 0) {
            throw new RuntimeException(
                sprintf('Command "%s" returns status code %s', $process->getCommandLine(), $composerStatus)
            );
        }

        return json_decode($process->getOutput(), true);
    }

    private function download(string $url, string $tempArchive): bool
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', $url);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $fh = fopen($tempArchive, 'wb');
        fwrite($fh, $response->getContent());
        fclose($fh);

        return true;
    }

    private function extract(string $tempArchive): ?string
    {
        try {
            $phar = new PharData($tempArchive);
            $phar->extractTo(sys_get_temp_dir(), null, true);
            $targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($phar->current()->getPathname());
        } catch (Exception $e) {
            return null;
        }
        return $targetDir;
    }

    /**
     * @return array<string, mixed>
     */
    private function analyse(string $packageDir): ?array
    {
        $process = new Process([$this->compatInfoBin, 'analyser:run', '--output=json', $packageDir]);
        $process->start();
        while ($process->isRunning()) {
            // waiting for process to finish
        }

        $processStatus = $process->getExitCode();
        if ($processStatus > 0) {
            throw new RuntimeException(
                sprintf('Command "%s" returns status code %s', $process->getCommandLine(), $processStatus)
            );
        }

        $matches = [];
        if (!preg_match('/\/.*\.json/', $process->getOutput(), $matches)) {
            return null;
        }

        $jsonResult = json_decode(file_get_contents($matches[0]), true);
        $conditions = $jsonResult['Bartlett\CompatInfo\Application\Analyser\CompatibilityAnalyser']['conditions'];

        $constantsFound = [];
        $functionsFound = [];

        foreach ($conditions as $condition => $values) {
            $matches = [];
            preg_match(
                '/(extension_loaded|function_exists|method_exists|class_exists|interface_exists|trait_exists|defined)\((.*)\)/',
                $condition,
                $matches
            );

            if ($matches[1] === 'defined') {
                $constantsFound[$matches[2]] = [$values['ext.name'], $values['ext.min']];
            } elseif ($matches[1] === 'function_exists') {
                $functionsFound[$matches[2]] = [$values['ext.name'], $values['ext.min']];
            }
        }

        return [
            'constants' => $constantsFound,
            'functions' => $functionsFound,
        ];
    }

    /**
     * @param array<string, mixed> $analysisResults
     */
    private function updateReference(string $major, array $analysisResults, string $packageName): bool
    {
        $referencesUpdated = false;
        $refDir = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 4), 'data', 'reference', 'extension']);
        $flags = FilesystemIterator::SKIP_DOTS;
        $refs = new RecursiveDirectoryIterator($refDir, $flags);

        $extensions = [];
        foreach ($refs as $ref) {
            $extensions[$ref->getBasename()] = $ref->getPathname();
        }
        unset($refs);

        foreach ($analysisResults as $component => $results) {
            foreach ($results as $name => $properties) {
                $refPathname = $extensions[$properties[0]];
                $meta = $this->jsonFileHandler->read($refPathname, $component, $major);
                if (!is_array($meta)) {
                    continue;
                }
                $shouldRewrite = false;
                foreach ($meta as $index => $item) {
                    if ($item['name'] === $name) {
                        $meta[$index]['polyfill'] = $packageName;
                        $shouldRewrite = true;
                    }
                }
                if ($shouldRewrite) {
                    $this->jsonFileHandler->write($refPathname, $component, $major, $meta);
                    $referencesUpdated = true;
                }
            }
        }
        return $referencesUpdated;
    }
}
