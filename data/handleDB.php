<?php
/**
 * Script to handle compatinfo.sqlite file, that provides all references.
 *
 * CAUTION: uses at your own risk.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @since    Release 4.0.0alpha3 of PHP_CompatInfo
 * @since    Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . '/ReferenceCollection.php';

use Bartlett\CompatInfoDb\ExtensionFactory;
use Bartlett\CompatInfoDb\Environment;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * Common class to all 'db' commands
 */
class Command extends BaseCommand
{
    protected function readJsonFile($refName, $ext, $major)
    {
        $filename = $this->getApplication()->getRefDir() .
            '/' . ucfirst($refName) . $major . ".$ext.json";

        if (!file_exists($filename)) {
            return false;
        }
        $jsonStr = file_get_contents($filename);
        $data    = json_decode($jsonStr, true);
        return $data;
    }

    protected function writeJsonFile($refName, $ext, $major, $data)
    {
        $filename = $this->getApplication()->getRefDir() .
            '/' . ucfirst($refName) . $major . ".$ext.json";

        if (!file_exists($filename)) {
            return false;
        }
        $jsonStr = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $jsonStr);
    }

    /**
     * Prints the database current build version
     *
     * @param OutputInterface $output Console Output concrete instance
     */
    protected function printDbBuildVersion(OutputInterface $output)
    {
        $output->writeln(
            sprintf(
                '<info>Reference Database Version</info> => %s%s',
                Environment::versionRefDb()['build.version'],
                PHP_EOL
            )
        );
    }

    /**
     * Helper that convert analyser results to a console table
     *
     * @param OutputInterface $output  Console Output concrete instance
     * @param array           $headers All table headers
     * @param array           $rows    All table rows
     * @param string          $style   The default style name to render tables
     *
     * @return void
     */
    protected function tableHelper(OutputInterface $output, $headers, $rows, $style = 'compact')
    {
        $table = new Table($output);
        $table->setStyle($style)
            ->setHeaders($headers)
            ->setRows($rows)
            ->render()
        ;
    }

    /**
     * Helper that convert an array key-value pairs to a console report.
     *
     * See Structure and Loc analysers for implementation examples
     *
     * @param OutputInterface $output Console Output concrete instance
     * @param array           $lines  Any analyser formatted metrics
     *
     * @return void
     */
    protected function printFormattedLines(OutputInterface $output, array $lines)
    {
        foreach ($lines as $ident => $contents) {
            list ($format, $args) = $contents;
            $output->writeln(vsprintf($format, $args));
        }
    }
}

/**
 * Backup copy of the database
 */
class DbBackupCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:backup')
            ->setDescription('Backup the current SQLite compatinfo database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source  = $this->getApplication()->getDbFilename();

        $sha1 = sha1_file($source);
        $dest = dirname($source) . DIRECTORY_SEPARATOR . basename($source) . ".$sha1";

        copy($source, $dest);

        $output->writeln(
            sprintf(
                'Database <info>%s</info> sha1: <comment>%s</comment>' .
                ' was copied to <comment>%s</comment>',
                $source,
                $sha1,
                $dest
            )
        );
    }
}

/**
 * Initiliaze the database with JSON files for one or all extensions.
 */
class DbInitCommand extends Command
{
    private $extensions;

    protected function configure()
    {
        $this->setName('db:init')
            ->setDescription('Load JSON file(s) in SQLite database')
            ->addArgument(
                'extension',
                InputArgument::OPTIONAL,
                'extension to load in database (case insensitive)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getApplication()->getRefDir();

        $iterator = new \DirectoryIterator($path);
        $suffix   = '.extensions.json';

        foreach ($iterator as $file) {
            if (fnmatch('*'.$suffix, $file->getPathName())) {
                $className = str_replace($suffix, '', basename($file));
                $extName   = strtolower($className);

                $this->extensions[] = $extName;
            }
        }

        $extension = trim($input->getArgument('extension'));
        $extension = strtolower($extension);

        if (empty($extension)) {
            $extensions = $this->extensions;
        } else {
            if (!in_array($extension, $this->extensions)) {
                $output->writeln(
                    sprintf('<error>Extension %s does not exist.</error>', $extension)
                );
                return;
            }
            $extensions = array($extension);
        }

        // do a DB backup first
        $command = $this->getApplication()->find('db:backup');
        $arguments = array(
            'command' => 'db:backup',
        );
        $input = new ArrayInput($arguments);
        $returnCode = $command->run($input, $output);

        if ($returnCode !== 0) {
            $output->writeln('<error>DB backup not performed</error>');
            return;
        }

        // then delete current DB before to init a new copy again
        unlink($this->getApplication()->getDbFilename());

        $pdo = new \PDO('sqlite:' . $this->getApplication()->getDbFilename());
        $ref = new ReferenceCollection($pdo);

        $max = count($extensions);

        $progress = new ProgressBar($output, $max);
        $progress->setFormat(' %percent:3s%% %elapsed:6s% %memory:6s% %message%');
        $progress->setMessage('');

        $progress->start();

        foreach ($extensions as $refName) {
            $pdo->beginTransaction();

            $ext  = 'extensions';
            $progress->setMessage(
                sprintf("Building %s (%s)", $ext, $refName)
            );
            $progress->display();
            $data = $this->readJsonFile($refName, $ext, '');
            $ref->addExtension($data);

            $ext  = 'releases';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addRelease($rec);
            }

            $ext  = 'interfaces';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addInterface($rec);
            }

            $ext  = 'classes';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addClass($rec);
            }

            $ext  = 'functions';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addFunction($rec);
            }

            $ext  = 'constants';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addConstant($rec);
            }

            $ext  = 'iniEntries';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addIniEntry($rec);
            }

            $ext  = 'const';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addClassConstant($rec);
            }

            $ext  = 'methods';
            $data = $this->readData($refName, $ext);
            foreach ($data as $rec) {
                $ref->addMethod($rec);
            }

            $pdo->commit();
            $progress->advance();
        }
        $time = time();
        $ref->addVersion(
            array(
                'build_string'  => date('M d Y H:i:s T', $time),
                'build_date'    => date('YmdHis', $time),
                'build_version' => $this->getApplication()->getVersion(),
            )
        );
        $progress->setMessage('Database is built');
        $progress->display();
        $progress->finish();
        $output->writeln('');
    }

    /**
     * Reads splitted JSON data files
     */
    private function readData($refName, $ext)
    {
        $majorReleases = array(
            'core' => array(
                'classes'    => array('4', '5', '7', '71'),
                'constants'  => array('4', '5', '71'),
                'functions'  => array('4', '5', '7'),
                'iniEntries' => array('4', '5', '7', '71'),
                'interfaces' => array('5', '7', '72'),
                'releases'   => array('4', '5', '70', '71', '72'),
            ),
            'standard' => array(
                'classes'    => array('4', '5', '7'),
                'constants'  => array('4', '5', '7'),
                'functions'  => array('4', '5', '7', '71', '72'),
                'iniEntries' => array('4', '5', '7', '71'),
                'releases'   => array('4', '5', '7', '72'),
                'methods'    => array('4', '5', '7', '71'),
            ),
            'apcu' => array(
                'classes'    => array('5'),
                'constants'  => array(''),
                'functions'  => array('', '5'),
                'methods'    => array('5'),
                'releases'   => array('', '5'),
            ),
            'ast' => array(
                'classes'    => array(''),
                'constants'  => array(''),
                'functions'  => array(''),
                'methods'    => array(''),
                'releases'   => array(''),
            ),
            'bcmath' => array(
                'releases'   => array('', '70', '71'),
            ),
            'bz2' => array(
                'releases'   => array('', '70', '71'),
            ),
            'calendar' => array(
                'releases'   => array('', '70', '71'),
            ),
            'ctype' => array(
                'releases'   => array('', '70', '71'),
            ),
            'curl' => array(
                'functions'  => array('', '71'),
                'releases'   => array('', '70', '71'),
            ),
            'date' => array(
                'const'      => array('', '70', '71', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'dom' => array(
                'classes'    => array(''),
                'constants'  => array(''),
                'functions'  => array(''),
                'methods'    => array(''),
                'releases'   => array(''),
            ),
            'filter' => array(
                'constants'  => array('', '70', '71'),
                'releases'   => array('', '70', '71'),
            ),
            'ftp' => array(
                'constants'  => array('', '56'),
                'functions'  => array('', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'gd' => array(
                'functions'  => array('', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'gender' => array(
                'classes'    => array(''),
                'releases'   => array('', '1'),
                'const'      => array('', '1'),
                'methods'    => array(''),
            ),
            'geoip' => array(
                'iniEntries' => array('1'),
                'constants'  => array('', '1'),
                'functions'  => array('', '1'),
                'releases'   => array('', '1'),
            ),
            'gmp' => array(
                'releases'   => array('', '70', '71'),
            ),
            'hash' => array(
                'functions'  => array('', '71', '72'),
            ),
            'haru' => array(
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'htscanner' => array(
                'iniEntries' => array('', '1'),
                'releases'   => array('', '1'),
            ),
            'http' => array(
                'classes'    => array('', '1', '2'),
                'constants'  => array('', '2'),
                'functions'  => array(''),
                'iniEntries' => array('', '2'),
                'interfaces' => array('2', '3'),
                'releases'   => array('', '1', '2', '3'),
                'const'      => array('2', '3'),
                'methods'    => array('2'),
            ),
            'imagick' => array(
                'classes'    => array(''),
                'const'      => array(''),
                'iniEntries' => array(''),
                'releases'   => array(''),
            ),
            'igbinary' => array(
                'functions'  => array('1'),
                'iniEntries' => array('1'),
                'releases'   => array('1', '2'),
            ),
            'intl' => array(
                'classes'    => array('1', '2', '5', '70'),
                'constants'  => array('1', '2'),
                'functions'  => array('1', '2', '5'),
                'iniEntries' => array('1', '3'),
                'releases'   => array('1', '2', '3', '5'),
                'const'      => array('1', '2', '5', '70'),
                'methods'    => array('1', '2', '5', '70', '71'),
            ),
            'jsmin' => array(
                'constants'  => array(''),
                'functions'  => array(''),
                'releases'   => array('', '1', '2'),
            ),
            'ldap' => array(
                'constants'  => array('', '70', '71'),
                'functions'  => array('', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'lzf' => array(
                'functions'  => array('', '1'),
                'releases'   => array('', '1'),
            ),
            'mailparse' => array(
                'classes'    => array(''),
                'constants'  => array(''),
                'functions'  => array(''),
                'iniEntries' => array(''),
                'releases'   => array('', '2', '3'),
                'methods'    => array(''),
            ),
            'mbstring' => array(
                'functions'  => array('', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'mongo' => array(
                'classes'    => array('', '1'),
                'constants'  => array('1'),
                'functions'  => array('1'),
                'iniEntries' => array(''),
                'interfaces' => array('1'),
                'releases'   => array('', '1'),
                'const'      => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'msgpack' => array(
                'classes'    => array(''),
                'constants'  => array('2'),
                'functions'  => array(''),
                'iniEntries' => array(''),
                'releases'   => array('', '2'),
                'const'      => array(''),
                'methods'    => array(''),
            ),
            'mysqli' => array(
                'releases'   => array('', '70', '71'),
            ),
            'openssl' => array(
                'constants'  => array('', '71'),
                'functions'  => array('', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'oauth' => array(
                'classes'    => array('', '1'),
                'constants'  => array('', '1'),
                'functions'  => array(''),
                'releases'   => array('', '1', '2'),
                'methods'    => array('', '1'),
            ),
            'pcre' => array(
                'iniEntries' => array('', '70'),
                'functions'  => array('', '70'),
            ),
            'pcntl' => array(
                'functions' => array('', '70', '71'),
            ),
            'posix' => array(
                'functions' => array('', '70'),
            ),
            'pdflib' => array(
                'classes'    => array('2'),
                'functions'  => array('2', '3'),
                'releases'   => array('1', '2', '3'),
                'methods'    => array('2', '3'),
            ),
            'pgsql' => array(
                'constants'  => array('', '71'),
                'releases'   => array('', '70', '71'),
            ),
            'pthreads' => array(
                'classes'    => array('', '1', '2'),
                'constants'  => array('', '2'),
                'releases'   => array('', '1', '2', '3'),
                'methods'    => array('', '1', '2', '3'),
            ),
            'raphf' => array(
                'iniEntries' => array('2'),
                'functions'  => array('2'),
                'releases'   => array('2'),
            ),
            'rar' => array(
                'classes'    => array('2'),
                'constants'  => array('2'),
                'functions'  => array('2', '3'),
                'releases'   => array('', '1', '2', '3', '4'),
                'const'      => array('', '2', '4'),
                'methods'    => array('', '2', '3', '4'),
            ),
            'redis' => array(
                'classes'    => array('2'),
                'iniEntries' => array('2', '3', '4'),
                'releases'   => array('2', '3', '4'),
                'const'      => array('2', '4'),
                'methods'    => array('2', '3'),
            ),
            'riak' => array(
                'classes'    => array('', '1'),
                'iniEntries' => array('', '1'),
                'interfaces' => array('', '1'),
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'session' => array(
                'interfaces' => array('', '70'),
                'functions'  => array('', '71'),
                'iniEntries' => array('', '70', '71'),
                'releases'   => array('', '70', '71'),
            ),
            'shmop' => array(
                'releases'   => array('', '70', '71'),
            ),
            'soap' => array(
                'methods'    => array(''),
                'releases'   => array('', '70', '71'),
            ),
            'sockets' => array(
                'constants'  => array('', '70'),
                'functions'  => array('', '70', '72'),
                'releases'   => array('', '70', '71'),
            ),
            'solr' => array(
                'classes'    => array('', '1', '2'),
                'constants'  => array(''),
                'functions'  => array(''),
                'releases'   => array('', '1', '2'),
                'const'      => array('', '2'),
                'methods'    => array('', '1', '2'),
            ),
            'sphinx' => array(
                'classes'    => array(''),
                'constants'  => array('', '1'),
                'releases'   => array('', '1'),
                'methods'    => array(''),
            ),
            'spl' => array(
                'functions'  => array('', '72'),
                'methods'    => array('5', '70'),
                'releases'   => array('', '70', '71'),
            ),
            'sqlite3' => array(
                'constants'  => array('', '71'),
                'methods'    => array(''),
                'releases'   => array('', '70', '71'),
            ),
            'ssh2' => array(
                'releases'   => array('', '1'),
            ),
            'stomp' => array(
                'classes'    => array(''),
                'iniEntries' => array('', '1'),
                'functions'  => array('', '1'),
                'releases'   => array('', '1', '2'),
                'methods'    => array('', '1'),
            ),
            'svn' => array(
                'classes'    => array(''),
                'constants'  => array('', '1'),
                'functions'  => array(''),
                'releases'   => array('', '1'),
            ),
            'tidy' => array(
                'releases'   => array('', '70', '71'),
                'methods'    => array(''),
            ),
            'tokenizer' => array(
                'constants'  => array('', '70')
            ),
            'uopz' => array(
                'constants'  => array('2'),
                'functions'  => array('2', '5'),
                'iniEntries' => array('2', '5'),
                'releases'   => array('2', '5'),
            ),
            'uploadprogress' => array(
                'functions'  => array(''),
                'iniEntries' => array(''),
                'releases'   => array('', '1'),
            ),
            'varnish' => array(
                'classes'    => array(''),
                'constants'  => array(''),
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'xdebug' => array(
                'constants'  => array('2'),
                'functions'  => array('1', '2'),
                'iniEntries' => array('1', '2'),
                'releases'   => array('1', '2'),
            ),
            'xmldiff' => array(
                'classes'    => array(''),
                'releases'   => array('', '1'),
                'methods'    => array(''),
            ),
            'xmlrpc' => array(
                'releases'   => array('', '70', '71'),
            ),
            'xsl' => array(
                'releases'   => array('', '70', '71'),
            ),
            'zendopcache' => array(
                'functions'  => array('7'),
                'releases'   => array('7'),
                'iniEntries' => array('', '7', '71'),
                'releases'   => array('', '7', '71'),
            ),
            'zip' => array(
                'functions'  => array('1'),
                'releases'   => array('1'),
                'classes'    => array('1'),
                'methods'    => array('1'),
                'const'      => array('1'),
            ),
            'zlib' => array(
                'functions'  => array('', '72'),
                'releases'   => array(''),
            ),
        );

        if (array_key_exists($refName, $majorReleases)) {
            $iterations = $majorReleases[$refName];
            if (array_key_exists($ext, $iterations)) {
                $iterations = $iterations[$ext];
            } else {
                $iterations = array('');
            }
        } else {
            $iterations = array('');
        }

        $data = array();

        foreach ($iterations as $major) {
            $temp = $this->readJsonFile($refName, $ext, $major);
            if (!$temp) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    // missing files are optional until all extensions are fully documented
                    continue;
                } else {
                    $error = sprintf('Cannot decode file %s%s.%s.json', $refName, $major, $ext);
                }
                throw new \RuntimeException($error);
            }
            $data = array_merge($data, $temp);
        }
        return $data;
    }
}

/**
 * Build Extension draft JSON data
 */
class DbBuildExtCommand extends Command
{
    private $stmtClass;

    protected function configure()
    {
        $this->setName('db:build:ext')
            ->setDescription('Build Extension draft JSON data for SQLite compatinfo database')
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                'extension to extract components (case insensitive)'
            )
            ->addArgument(
                'ext_min',
                InputArgument::OPTIONAL,
                'extension MIN version',
                '0.1.0'
            )
            ->addArgument(
                'php_min',
                InputArgument::OPTIONAL,
                'php MIN version',
                '5.3.0'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extension = trim($input->getArgument('extension'));
        $extMin    = trim($input->getArgument('ext_min'));
        $phpMin    = trim($input->getArgument('php_min'));

        $pdo = Environment::initRefDb();

        $stmtExtension = $pdo->prepare(
            'SELECT e.id as "ext_name_fk", e.name as "name"' .
            ' FROM bartlett_compatinfo_extensions e' .
            ' WHERE e.name = :name COLLATE NOCASE'
        );

        $this->stmtClass = $pdo->prepare(
            'SELECT c.ext_min as "ext_min", c.php_min as "php_min"' .
            ' FROM bartlett_compatinfo_classes c' .
            ' WHERE c.name = :name COLLATE NOCASE'
        );

        $inputParameters = array(':name' => $extension);
        $stmtExtension->execute($inputParameters);
        $result = $stmtExtension->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // unknown extension
            $extId   = -1;
            $extName = $extension;
        } else {
            $extId   = $result['ext_name_fk'];
            $extName = $result['name'];
        }

        // EXTENSIONS
        $data = ['id' => $extId, 'name' => $extName];
        $output->writeln(
            sprintf(
                '<info>%s.extensions.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $output->writeln(
            json_encode($data, JSON_PRETTY_PRINT)
        );

        $re = new \ReflectionExtension($extension);
        $classes = $re->getClassNames();
        natsort($classes);

        if (is_array($classes)) {
            // CLASSES
            $output->writeln(
                sprintf(
                    '<info>%s.classes.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildClasses($extId, $extMin, $phpMin, $re, $output);

            // INTERFACES
            $output->writeln(
                sprintf(
                    '<info>%s.interfaces.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildInterfaces($extId, $extMin, $phpMin, $re, $output);

            // METHODS
            $output->writeln(
                sprintf(
                    '<info>%s.methods.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildMethods($extId, $extMin, $phpMin, $classes, $output);

            // CLASSES CONSTANTS
            $output->writeln(
                sprintf(
                    '<info>%s.const.json</info>',
                    ucfirst(strtolower($extension))
                )
            );
            $this->buildClassConstants($extId, $extMin, $phpMin, $classes, $output);
        }

        // CONSTANTS
        $output->writeln(
            sprintf(
                '<info>%s.constants.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $this->buildConstants($extId, $extMin, $phpMin, $re, $output);

        // FUNCTIONS
        $output->writeln(
            sprintf(
                '<info>%s.functions.json</info>',
                ucfirst(strtolower($extension))
            )
        );
        $this->buildFunctions($extId, $extMin, $phpMin, $re, $output);
    }

    private function buildClasses($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if ($rc->isInterface()) {
                continue;
            }

            $data = [
                'ext_name_fk' => $extId,
                'name' => $className,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildInterfaces($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getClasses() as $className => $rc) {
            if (!$rc->isInterface()) {
                continue;
            }

            $data = [
                'ext_name_fk' => $extId,
                'name' => $className,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
            ];
            $objects[$className] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildMethods($extId, $extMinDefault, $phpMinDefault, array $classes, OutputInterface $output)
    {
        $methods = [];

        foreach ($classes as $className) {
            $rc = new \ReflectionClass($className);

            $inputParameters = array(':name' => $className);
            $this->stmtClass->execute($inputParameters);
            $result = $this->stmtClass->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // pre-set ext and php MIN versions to better values than user custom (console inputs)
                $extMin = $result['ext_min'];
                $phpMin = $result['php_min'];
            } else {
                $extMin = $extMinDefault;
                $phpMin = $phpMinDefault;
            }

            $meth = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
            natsort($meth);

            foreach ($meth as $method) {
                $methodName = $method->getName();

                try {
                    $method->getPrototype();
                    // if there is one method prototype, don't check it
                    if ($output->isVerbose()) {
                        $output->writeln(
                            sprintf('<comment>%s</comment> is a prototype', $methodName)
                        );
                    }
                    continue;
                } catch (\ReflectionException $e) {
                }

                $from = $method->getDeclaringClass()->getName();

                if ($from !== $className) {
                    if ($output->isVerbose()) {
                        $output->writeln(
                            sprintf('<comment>%s</comment> inherit from <comment>%s</comment>', $methodName, $from)
                        );
                    }
                    continue;
                }

                $static = $method->isStatic() ? 'true' : 'false';

                $data = [
                    'ext_name_fk' => $extId,
                    'class_name' => $className,
                    'name' => $methodName,
                    'static' => $static,
                    'ext_min' => $extMin,
                    'ext_max' => '',
                    'php_min' => $phpMin,
                    'php_max' => '',
                ];
                $methods[] = $data;
            }
        }

        $output->writeln(
            json_encode($methods, JSON_PRETTY_PRINT)
        );
    }

    private function buildClassConstants($extId, $extMinDefault, $phpMinDefault, array $classes, OutputInterface $output)
    {
        $constants = [];

        foreach ($classes as $className) {
            $rc = new \ReflectionClass($className);

            $inputParameters = array(':name' => $className);
            $this->stmtClass->execute($inputParameters);
            $result = $this->stmtClass->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // pre-set ext and php MIN versions to better values than user custom (console inputs)
                $extMin = $result['ext_min'];
                $phpMin = $result['php_min'];
            } else {
                $extMin = $extMinDefault;
                $phpMin = $phpMinDefault;
            }

            $const = array_keys($rc->getConstants());
            natsort($const);

            foreach ($const as $constName) {
                $data = [
                    'ext_name_fk' => $extId,
                    'class_name' => $className,
                    'name' => $constName,
                    'ext_min' => $extMin,
                    'ext_max' => '',
                    'php_min' => $phpMin,
                    'php_max' => '',
                ];
                $constants[] = $data;
            }
        }

        $output->writeln(
            json_encode($constants, JSON_PRETTY_PRINT)
        );
    }

    private function buildConstants($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getConstants() as $name => $value) {

            $data = [
                'ext_name_fk' => $extId,
                'name' => $name,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
                'php_excludes' => '',
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }

    private function buildFunctions($extId, $extMin, $phpMin, \ReflectionExtension $re, OutputInterface $output)
    {
        $objects = [];

        foreach ($re->getFunctions() as $name => $rf) {

            $data = [
                'ext_name_fk' => $extId,
                'name' => $name,
                'ext_min' => $extMin,
                'ext_max' => '',
                'php_min' => $phpMin,
                'php_max' => '',
                'parameters' => '',
                'php_excludes' => '',
            ];
            $objects[$name] = $data;
        }

        ksort($objects, SORT_NATURAL);

        $output->writeln(
            json_encode(array_values($objects), JSON_PRETTY_PRINT)
        );
    }
}

/**
 * Update JSON files when a new PHP version is released.
 */
class DbReleaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:release:php')
            ->setDescription('Fix php.max versions on new PHP release')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $latest  = array();

        $refName = 'Curl';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'CURLCLOSEPOLICY_CALLBACK'              => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_RECENTLY_USED'   => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_TRAFFIC'         => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_OLDEST'                => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_SLOWEST'               => ExtensionFactory::LATEST_PHP_5_5,
            'CURLOPT_CLOSEPOLICY'                   => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'allow_call_time_pass_reference'        => ExtensionFactory::LATEST_PHP_5_3,
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'highlight.bg'                          => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_gpc'                      => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_sybase'                   => ExtensionFactory::LATEST_PHP_5_3,
            'register_globals'                      => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode'                             => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_exec_dir'                    => ExtensionFactory::LATEST_PHP_5_3,
            'y2k_compliance'                        => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_gid'                         => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_include_dir'                 => ExtensionFactory::LATEST_PHP_5_3,

            'always_populate_raw_post_data'         => ExtensionFactory::LATEST_PHP_5_6,
            'asp_tags'                              => ExtensionFactory::LATEST_PHP_5_6,

            'exit_on_timeout'                       => ExtensionFactory::LATEST_PHP_7_0,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'register_long_arrays'                  => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'constants';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'ZEND_MULTIBYTE'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILEINFO_COMPRESS'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '0.7.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            '1.0.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.3.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.5.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'HttpRequest'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponse'                          => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUtil'                              => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'HttpDeflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpEncodingException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpException'                         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpHeaderException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInvalidParamException'             => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMalformedHeadersException'         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessage'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessageTypeException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryString'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryStringException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestMethodException'            => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPool'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPoolException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponseException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRuntimeException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpSocketException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUrlException'                      => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestDataShare'                  => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Iconv';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_iconv_handler'                      => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'datefmt_set_timezone_id'               => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'methods';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            // IntlDateFormatter
            'setTimeZoneId'                         => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mcrypt_ecb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cbc'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cfb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_ofb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_generic_end'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mysqli_bind_param'                     => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_bind_result'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_client_encoding'                => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_disable_reads_from_master'      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_disable_rpl_parse'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_reads_from_master'       => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_rpl_parse'               => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_fetch'                          => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_get_metadata'                   => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_master_query'                   => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_param_count'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_rpl_parse_enabled'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_probe'                      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_query_type'                 => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_send_long_data'                 => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_send_query'                     => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_slave_query'                    => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'MYSQLI_RPL_ADMIN'                      => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_MASTER'                     => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_SLAVE'                      => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oauth';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            // OAuth
            '__destruct'                            => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session_is_registered'                 => ExtensionFactory::LATEST_PHP_5_3,
            'session_register'                      => ExtensionFactory::LATEST_PHP_5_3,
            'session_unregister'                    => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session.entropy_file'                  => ExtensionFactory::LATEST_PHP_7_0,
            'session.entropy_length'                => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_function'                 => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_bits_per_character'       => ExtensionFactory::LATEST_PHP_7_0,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'interfaces';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'ArrayAccess'                           => ExtensionFactory::LATEST_PHP_5_2,
            'Countable'                             => ExtensionFactory::LATEST_PHP_7_1,
            'Iterator'                              => ExtensionFactory::LATEST_PHP_5_2,
            'IteratorAggregate'                     => ExtensionFactory::LATEST_PHP_5_2,
            'Serializable'                          => ExtensionFactory::LATEST_PHP_5_2,
            'Traversable'                           => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SimpleXMLIterator'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'safe_mode_allowed_env_vars'            => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_protected_env_vars'          => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'php_logo_guid'                         => ExtensionFactory::LATEST_PHP_5_4,
            'php_real_logo_guid'                    => ExtensionFactory::LATEST_PHP_5_4,
            'zend_logo_guid'                        => ExtensionFactory::LATEST_PHP_5_4,
            'php_egg_logo_guid'                     => ExtensionFactory::LATEST_PHP_5_4,
            'import_request_variables'              => ExtensionFactory::LATEST_PHP_5_3,

            'call_user_method'                      => ExtensionFactory::LATEST_PHP_5_6,
            'call_user_method_array'                => ExtensionFactory::LATEST_PHP_5_6,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_6,
            'set_magic_quotes_runtime'              => ExtensionFactory::LATEST_PHP_5_6,
            'set_socket_blocking'                   => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'constants';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'STREAM_ENFORCE_SAFE_MODE'              => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tidy';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_tidyhandler'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tokenizer';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'T_BAD_CHARACTER'                       => ExtensionFactory::LATEST_PHP_5_6,
            'T_CHARACTER'                           => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xsl';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'xsl.security_prefs'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Zendopcache';
        $ext     = 'iniEntries';
        $major   = '7';
        $entry   = 'php_max';
        $names   = array(
            'opcache.load_comments'                 => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        // tag MAX version
        while (!empty($latest)) {
            list($refName, $ext, $major, $entry, $names) = array_pop($latest);

            $data = $this->readJsonFile($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    $error = sprintf('File %s.%s.json does not exist.', $refName, $ext);
                } else {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName, $ext);
                }

                $output->writeln(
                    sprintf('<error>%s</error>', $error)
                );
                return;
            }

            $key = $ext == 'releases' ? 'rel_version' : 'name';

            foreach ($data as &$element) {
                if (array_key_exists($element[$key], $names)) {
                    $element[$entry] = $names[$element[$key]];
                } elseif (array_key_exists('*', $names)) {
                    $element[$entry] = $names['*'];
                }
            }
            $this->writeJsonFile($refName, $ext, $major, $data);
        }
    }
}

/**
 * Add NEW release on each extensions that follow PHP version tagging strategy
 */
class DbPublishCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:publish:php')
            ->setDescription('Add new PHP release')
            ->addArgument(
                'rel_version',
                InputArgument::REQUIRED,
                'New PHP release version'
            )
            ->addArgument(
                'rel_date',
                InputArgument::OPTIONAL,
                'New PHP release date',
                date('Y-m-d')
            )
            ->addArgument(
                'rel_state',
                InputArgument::OPTIONAL,
                'New PHP release state (alpha, beta, RC, stable)',
                'stable'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $relVersion = trim($input->getArgument('rel_version'));
        $relDate    = trim($input->getArgument('rel_date'));
        $relState   = trim($input->getArgument('rel_state'));

        list($maj, $min, $rel) = sscanf($relVersion, '%d.%d.%s');

        $release = [];

        $extId   = 7;
        $refName = 'Core';
        $ext     = 'releases';
        $major   = $maj . $min;
        $release[] = array($extId, $refName, $ext, $major);

        $extId   = 78;
        $refName = 'Standard';
        $release[] = array($extId, $refName, $ext, $major);

        // @see  i.e: opcache extension version is now PHP version since 7.0.8RC1
        // @link https://github.com/llaville/php-compatinfo-db/issues/5
        $extId   = 100;
        $refName = 'Zendopcache';
        $release[] = array($extId, $refName, $ext, $major);

        // bcmath extension version is now PHP version since 7.0.0alpha1
        $extId   = 4;
        $refName = 'Bcmath';
        $release[] = array($extId, $refName, $ext, $major);

        // bz2 extension version is now PHP version since 7.0.0alpha1
        $extId   = 5;
        $refName = 'Bz2';
        $release[] = array($extId, $refName, $ext, $major);

        // calendar extension version is now PHP version since 7.0.0alpha1
        $extId   = 6;
        $refName = 'Calendar';
        $release[] = array($extId, $refName, $ext, $major);

        // ctype extension version is now PHP version since 7.0.0alpha1
        $extId   = 8;
        $refName = 'Ctype';
        $release[] = array($extId, $refName, $ext, $major);

        // curl extension version is now PHP version since 7.0.0alpha1
        $extId   = 9;
        $refName = 'Curl';
        $release[] = array($extId, $refName, $ext, $major);

        // date extension version is now PHP version since 7.0.0alpha1
        $extId   = 10;
        $refName = 'Date';
        $release[] = array($extId, $refName, $ext, $major);

        // dom extension version does not follow PHP Version
        $extId   = 11;

        // enchant extension version does not follow PHP Version
        $extId   = 12;

        // ereg extension was deprecated since PHP 5.3 and was removed in PHP 7
        $extId   = 13;

        // exif extension version does not follow PHP Version
        $extId   = 14;

        // fileinfo extension version does not follow PHP Version
        $extId   = 15;

        // filter extension version is now PHP version since 7.0.0alpha1
        $extId   = 16;
        $refName = 'Filter';
        $release[] = array($extId, $refName, $ext, $major);

        // ftp extension version is now PHP version since 7.0.0alpha1
        $extId   = 17;
        $refName = 'Ftp';
        $release[] = array($extId, $refName, $ext, $major);

        // gd extension version is now PHP version since 7.0.0alpha1
        $extId   = 18;
        $refName = 'Gd';
        $release[] = array($extId, $refName, $ext, $major);

        // geoip extension version does not follow PHP Version
        $extId   = 20;

        // gmp extension version is now PHP version since 7.0.0alpha1
        $extId   = 22;
        $refName = 'Gmp';
        $release[] = array($extId, $refName, $ext, $major);

        // intl extension version does not follow PHP Version
        $extId   = 32;

        // ldap extension version is now PHP version since 7.0.0alpha1
        $extId   = 35;
        $refName = 'Ldap';
        $release[] = array($extId, $refName, $ext, $major);

        // lzf extension version does not follow PHP Version
        $extId   = 38;

        // mailparse extension version does not follow PHP Version
        $extId   = 39;

        // mbstring extension version is now PHP version since 7.0.0alpha1
        $extId   = 40;
        $refName = 'Mbstring';
        $release[] = array($extId, $refName, $ext, $major);

        // mysqli extension version is now PHP version since 7.0.0alpha1
        $extId   = 49;
        $refName = 'Mysqli';
        $release[] = array($extId, $refName, $ext, $major);

        // openssl extension version is now PHP version since 7.0.0alpha1
        $extId   = 52;
        $refName = 'Openssl';
        $release[] = array($extId, $refName, $ext, $major);

        // pgsql extension version is now PHP version since 7.0.0alpha1
        $extId   = 57;
        $refName = 'Pgsql';
        $release[] = array($extId, $refName, $ext, $major);

        // session extension version is now PHP version since 7.0.0alpha1
        $extId   = 66;
        $refName = 'Session';
        $release[] = array($extId, $refName, $ext, $major);

        // shmop extension version is now PHP version since 7.0.0alpha1
        $extId   = 67;
        $refName = 'Shmop';
        $release[] = array($extId, $refName, $ext, $major);

        // soap extension version is now PHP version since 7.0.0alpha1
        $extId   = 70;
        $refName = 'Soap';
        $release[] = array($extId, $refName, $ext, $major);

        // sockets extension version is now PHP version since 7.0.0alpha1
        $extId   = 71;
        $refName = 'Sockets';
        $release[] = array($extId, $refName, $ext, $major);

        // spl extension version is now PHP version since 7.0.0alpha1
        $extId   = 74;
        $refName = 'Spl';
        $release[] = array($extId, $refName, $ext, $major);

        // sqlite3 extension version is now PHP version since 7.0.0alpha1
        $extId   = 75;
        $refName = 'Sqlite3';
        $release[] = array($extId, $refName, $ext, $major);

        // tidy extension version is now PHP version since 7.0.0alpha1
        $extId   = 85;
        $refName = 'Tidy';
        $release[] = array($extId, $refName, $ext, $major);

        // xmlrpc extension version is now PHP version since 7.0.0alpha1
        $extId   = 95;
        $refName = 'Xmlrpc';
        $release[] = array($extId, $refName, $ext, $major);

        // xsl extension version is now PHP version since 7.0.0alpha1
        $extId   = 97;
        $refName = 'Xsl';
        $release[] = array($extId, $refName, $ext, $major);

        // Add NEW release on each extensions that follow PHP version tagging strategy
        while (!empty($release)) {
            list($extId, $refName, $ext, $major) = array_pop($release);

            $data = $this->readJsonFile($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName . $major, $ext);
                    $output->writeln(
                        sprintf('<error>%s</error>', $error)
                    );
                    return;
                }
                $data = [];
            }

            $data[] = [
                'ext_name_fk'   => $extId,
                'rel_version'   => $relVersion,
                'rel_date'      => $relDate,
                'rel_state'     => $relState,
                'ext_max'       => '',
                'php_min'       => $relVersion,
                'php_max'       => '',
            ];
            $this->writeJsonFile($refName, $ext, $major, $data);
        }
    }
}

/**
 * List all references supported by the Database.
 */
class DbListCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:list')
            ->setDescription('List all references supported by the Database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $factory = new ExtensionFactory(null);
        $refs    = $factory->getExtensions();
        $loaded  = 0;
        $headers = array('Reference', 'Version', 'State', 'Release Date', 'Loaded');
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

        $footers = array(
            '<info>Total</info>',
            sprintf('<info>[%d]</info>', count($refs)),
            '',
            '',
            sprintf('<info>[%d]</info>', $loaded)
        );

        $rows[] = new TableSeparator();
        $rows[] = $footers;

        // print results
        $this->printDbBuildVersion($output);
        $this->tableHelper($output, $headers, $rows);
    }
}

/**
 * Show details of a reference supported in the Database.
 */
class DbShowCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:show')
            ->setDescription('Show details of a reference supported in the Database.')
            ->addArgument(
                'extension',
                InputArgument::REQUIRED,
                'extension to extract components (case insensitive)'
            )
            ->addOption('releases', null, null, 'Show releases')
            ->addOption('ini', null, null, 'Show ini Entries')
            ->addOption('constants', null, null, 'Show constants')
            ->addOption('functions', null, null, 'Show functions')
            ->addOption('interfaces', null, null, 'Show interfaces')
            ->addOption('classes', null, null, 'Show classes')
            ->addOption('methods', null, null, 'Show methods')
            ->addOption('class-constants', null, null, 'Show class constants')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $releases       = $input->getOption('releases');
        $ini            = $input->getOption('ini');
        $constants      = $input->getOption('constants');
        $functions      = $input->getOption('functions');
        $interfaces     = $input->getOption('interfaces');
        $classes        = $input->getOption('classes');
        $methods        = $input->getOption('methods');
        $classConstants = $input->getOption('class-constants');


        $reference = new ExtensionFactory($input->getArgument('extension'));
        $results   = array();
        $summary   = array();

        $raw = $reference->getReleases();
        $summary['releases'] = count($raw);
        if ($releases) {
            $results['releases'] = $raw;
        }

        $raw = $reference->getIniEntries();
        $summary['iniEntries'] = count($raw);
        if ($ini) {
            $results['iniEntries'] = $raw;
        }

        $raw = $reference->getConstants();
        $summary['constants'] = count($raw);
        if ($constants) {
            $results['constants'] = $raw;
        }

        $raw = $reference->getFunctions();
        $summary['functions'] = count($raw);
        if ($functions) {
            $results['functions'] = $raw;
        }

        $raw = $reference->getInterfaces();
        $summary['interfaces'] = count($raw);
        if ($interfaces) {
            $results['interfaces'] = $raw;
        }

        $raw = $reference->getClasses();
        $summary['classes'] = count($raw);
        if ($classes) {
            $results['classes'] = $raw;
        }

        $raw = $reference->getClassConstants();
        $summary['class-constants'] = 0;
        foreach ($raw as $values) {
            $summary['class-constants'] += count($values);
        }
        if ($classConstants) {
            $results['class-constants'] = $raw;
        }

        $raw = $reference->getClassMethods();
        $summary['methods'] = 0;
        foreach ($raw as $values) {
            $summary['methods'] += count($values);
        }
        if ($methods) {
            $results['methods'] = $raw;
        }

        $raw = $reference->getClassStaticMethods();
        $summary['static methods'] = 0;
        foreach ($raw as $values) {
            $summary['static methods'] += count($values);
        }
        if ($methods) {
            $results['static methods'] = $raw;
        }

        if (empty($results)) {
            $results = array('summary' => $summary);
        }

        // print results
        $this->printDbBuildVersion($output);

        if (array_key_exists('summary', $results)) {
            $summary = $results['summary'];
            $output->writeln(sprintf('%s<info>Reference Summary</info>', PHP_EOL));
            $summary['releases'] = array(
                '  Releases                                  %10d',
                array($summary['releases'])
            );
            $summary['iniEntries'] = array(
                '  INI entries                               %10d',
                array($summary['iniEntries'])
            );
            $summary['constants'] = array(
                '  Constants                                 %10d',
                array($summary['constants'])
            );
            $summary['functions'] = array(
                '  Functions                                 %10d',
                array($summary['functions'])
            );
            $summary['interfaces'] = array(
                '  Interfaces                                %10d',
                array($summary['interfaces'])
            );
            $summary['classes'] = array(
                '  Classes                                   %10d',
                array($summary['classes'])
            );
            $summary['class-constants'] = array(
                '  Class Constants                           %10d',
                array($summary['class-constants'])
            );
            $summary['methods'] = array(
                '  Methods                                   %10d',
                array($summary['methods'])
            );
            $summary['static methods'] = array(
                '  Static Methods                            %10d',
                array($summary['static methods'])
            );
            $this->printFormattedLines($output, $summary);
            return;
        }

        foreach ($results as $title => $values) {
            $args = array();

            foreach ($values as $key => $val) {
                if (strcasecmp($title, 'releases') == 0) {
                    $key = sprintf('%s (%s)', $val['date'], $val['state']);

                } elseif (strcasecmp($title, 'methods') == 0
                    || strcasecmp($title, 'static methods') == 0
                    || strcasecmp($title, 'class-constants') == 0
                ) {
                    foreach ($val as $meth => $v) {
                        $k = sprintf('%s::%s', $key, $meth);
                        $args[$k] = $v;
                    }
                    continue;
                }
                $args[$key] = $val;
            }

            $rows = array();
            ksort($args);

            foreach ($args as $arg => $versions) {
                $row = array(
                    $arg,
                    self::ext($versions),
                    self::php($versions),
                    self::deprecated($versions),
                );
                $rows[] = $row;
            }

            $headers = array(ucfirst($title), 'EXT min/Max', 'PHP min/Max', 'Deprecated');
            $footers = array(
                sprintf('<info>Total [%d]</info>', count($args)),
                '',
                '',
                ''
            );
            $rows[] = new TableSeparator();
            $rows[] = $footers;

            $this->tableHelper($output, $headers, $rows);
            $output->writeln('');
        }
    }

    private static function ext($versions)
    {
        return empty($versions['ext.max'])
            ? $versions['ext.min']
            : $versions['ext.min'] . ' => ' . $versions['ext.max'];
    }

    private static function php($versions)
    {
        return empty($versions['php.max'])
            ? $versions['php.min']
            : $versions['php.min'] . ' => ' . $versions['php.max'];
    }

    private static function deprecated($versions)
    {
        if (isset($versions['deprecated'])) {
            return $versions['deprecated'];
        }
        return '';
    }
}

/**
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class DbHandleApplication extends Application
{
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new DbBackupCommand();
        $defaultCommands[] = new DbInitCommand();
        $defaultCommands[] = new DbBuildExtCommand();
        $defaultCommands[] = new DbReleaseCommand();
        $defaultCommands[] = new DbPublishCommand();
        $defaultCommands[] = new DbListCommand();
        $defaultCommands[] = new DbShowCommand();

        return $defaultCommands;
    }

    public function getDbFilename()
    {
        $database = 'compatinfo.sqlite';
        $source   = __DIR__ . '/' . $database;

        return $source;
    }

    // @deprecated Will be release in next major version
    public function getAppTempDir()
    {
        return sys_get_temp_dir() . '/bartlett';
    }

    public function getRefDir()
    {
        return __DIR__ . '/references';
    }

    public function getLongVersion()
    {
        $v = Environment::versionRefDb();

        return sprintf(
            '<info>%s</info> version <comment>%s</comment> DB built <comment>%s</comment>',
            $this->getName(),
            $this->getVersion(),
            $v['build.string']
        );
    }
}

$application = new DbHandleApplication('Database handler for CompatInfo', '1.37.0');
$application->run();
