<?php
/**
 * Script to handle compatinfo.sqlite file, that provides all references.
 *
 * CAUTION: uses at your own risk.
 *
 * @category PHP
 * @package  PHP_CompatInfo_Db
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
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
                'classes'    => array('4', '5', '7'),
                'constants'  => array('4', '5'),
                'functions'  => array('4', '5', '7'),
                'iniEntries' => array('4', '5', '7'),
                'interfaces' => array('5', '7'),
                'releases'   => array('4', '5', '7'),
            ),
            'standard' => array(
                'classes'    => array('4', '5', '7'),
                'constants'  => array('4', '5', '7'),
                'functions'  => array('4', '5', '7'),
                'iniEntries' => array('4', '5', '7'),
                'releases'   => array('4', '5', '7'),
                'methods'    => array('4', '5'),
            ),
            'gender' => array(
                'classes'    => array(''),
                'releases'   => array('', '1'),
                'const'      => array('', '1'),
                'methods'    => array(''),
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
                'interfaces' => array('2'),
                'releases'   => array('', '1', '2'),
                'const'      => array('2'),
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
                'classes'    => array('1', '2', '5'),
                'constants'  => array('1', '2'),
                'functions'  => array('1', '2', '5'),
                'iniEntries' => array('1', '3'),
                'releases'   => array('1', '2', '3', '5'),
                'const'      => array('1', '2', '5'),
                'methods'    => array('1', '2', '5'),
            ),
            'jsmin' => array(
                'constants'  => array(''),
                'functions'  => array(''),
                'releases'   => array('', '1', '2'),
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
                'releases'   => array('', '2'),
                'methods'    => array(''),
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
                'functions'  => array(''),
                'iniEntries' => array(''),
                'releases'   => array(''),
                'const'      => array(''),
                'methods'    => array(''),
            ),
            'oauth' => array(
                'classes'    => array('', '1'),
                'constants'  => array('', '1'),
                'functions'  => array(''),
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'pdflib' => array(
                'classes'    => array('2'),
                'functions'  => array('2', '3'),
                'releases'   => array('1', '2', '3'),
                'methods'    => array('2', '3'),
            ),
            'pthreads' => array(
                'classes'    => array('', '1', '2'),
                'constants'  => array('', '2'),
                'releases'   => array('', '1', '2', '3'),
                'methods'    => array('', '1', '2', '3'),
            ),
            'rar' => array(
                'classes'    => array('2'),
                'constants'  => array('2'),
                'functions'  => array('2', '3'),
                'releases'   => array('', '1', '2', '3'),
                'const'      => array('', '2'),
                'methods'    => array('', '2', '3'),
            ),
            'redis' => array(
                'classes'    => array('2'),
                'iniEntries' => array('2'),
                'releases'   => array('2'),
                'const'      => array('2'),
                'methods'    => array('2'),
            ),
            'riak' => array(
                'classes'    => array('', '1'),
                'iniEntries' => array('', '1'),
                'interfaces' => array('', '1'),
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
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
            'stomp' => array(
                'classes'    => array(''),
                'iniEntries' => array('', '1'),
                'functions'  => array('', '1'),
                'releases'   => array('', '1'),
                'methods'    => array('', '1'),
            ),
            'svn' => array(
                'classes'    => array(''),
                'constants'  => array('', '1'),
                'functions'  => array(''),
                'releases'   => array('', '1'),
            ),
            'uopz' => array(
                'constants'  => array('2'),
                'functions'  => array('2'),
                'iniEntries' => array('2'),
                'releases'   => array('2'),
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
            'zendopcache' => array(
                'functions'  => array('7'),
                'releases'   => array('7'),
                'iniEntries' => array('7'),
            ),
            'zip' => array(
                'functions'  => array('1'),
                'releases'   => array('1'),
                'classes'    => array('1'),
                'methods'    => array('1'),
                'const'      => array('1'),
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

        $refName = 'Spl';
        $ext     = 'interfaces';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'ArrayAccess'                           => ExtensionFactory::LATEST_PHP_5_2,
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
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class DbHandleApplication extends Application
{
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new DbBackupCommand();
        $defaultCommands[] = new DbInitCommand();
        $defaultCommands[] = new DbReleaseCommand();

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

$application = new DbHandleApplication('Database handler for CompatInfo', '1.22.0');
$application->run();
