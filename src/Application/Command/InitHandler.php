<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\ReferenceCollection;
use Symfony\Component\Console\Helper\ProgressBar;

class InitHandler implements CommandHandlerInterface
{
    private $jsonFileHandler;
    private $extensions;

    public function __construct($jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(InitCommand $command): void
    {
        $iterator = new \DirectoryIterator($command->refDir);
        $suffix   = '.extensions.json';

        foreach ($iterator as $file) {
            if (fnmatch('*'.$suffix, $file->getPathName())) {
                $className = str_replace($suffix, '', $file->getBasename());
                $extName   = strtolower($className);

                $this->extensions[] = $extName;
            }
        }

        $extension = $command->extension;
        $output = $command->output;

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


        // delete current DB before to init a new copy again
        unlink($command->dbFilename);

        $pdo = new \PDO('sqlite:' . $command->dbFilename);
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
            $data = $this->jsonFileHandler->read($refName, $ext, '');
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
                'build_version' => $command->appVersion,
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
    private function readData(string $refName, string $ext) : array
    {
        $majorReleases = array(
            'core' => array(
                'classes'    => array('4', '5', '7', '71'),
                'constants'  => array('4', '5', '71'),
                'functions'  => array('4', '5', '7', '73', '74'),
                'iniEntries' => array('4', '5', '7', '71', '73', '74'),
                'interfaces' => array('5', '7', '72'),
                'releases'   => array('4', '5', '70', '71', '72', '73', '74'),
            ),
            'standard' => array(
                'classes'    => array('4', '5', '7'),
                'constants'  => array('4', '5', '7', '71'),
                'functions'  => array('4', '5', '7', '71', '72', '73', '74'),
                'iniEntries' => array('4', '5', '7', '71', '74'),
                'releases'   => array('4', '5', '7', '72', '73', '74'),
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
                'constants'  => array('', '70'),
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
                'functions'  => array('', '72', '74'),
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
                'functions'  => array('', '73'),
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
            'imap' => array(
                'iniEntries' => array('56'),
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
                'releases'   => array('1', '2', '3'),
            ),
            'intl' => array(
                'classes'    => array('1', '2', '5', '70'),
                'constants'  => array('1', '2'),
                'functions'  => array('1', '2', '5', '73'),
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
                'functions'  => array('', '72', '73'),
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
                'functions'  => array('', '72', '74'),
                'iniEntries' => array('', '73', '74'),
                'releases'   => array('', '70', '71', '72', '73'),
            ),
            'memcache' => array(
                'iniEntries' => array('', '70'),
                'releases'   => array(''),
            ),
            'memcached' => array(
                'functions'  => array('3'),
                'iniEntries' => array('', '3'),
                'releases'   => array('', '3'),
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
            'oci8' => array(
                'classes'    => array(''),
                'constants'  => array(''),
                'functions'  => array(''),
                'iniEntries' => array(''),
                'releases'   => array('1', '2'),
                'methods'    => array(''),
            ),
            'openssl' => array(
                'constants'  => array('', '71'),
                'functions'  => array('', '72', '73', '74'),
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
                'functions' => array('', '70', '71', '74'),
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
            'reflection' => array(
                'classes'    => ['', '70', '71', '74'],
                'methods'    => ['', '74'],
            ),
            'redis' => array(
                'classes'    => array('2', '5'),
                'iniEntries' => array('2', '3', '4', '5'),
                'releases'   => array('2', '3', '4', '5'),
                'const'      => array('2', '4', '5'),
                'methods'    => array('2', '3', '5'),
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
                'iniEntries' => array('', '70', '71', '73'),
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
                'functions'  => array('', '1'),
                'releases'   => array('', '1', '2'),
                'const'      => array('1'),
                'methods'    => array('1'),
            ),
            'tidy' => array(
                'releases'   => array('', '70', '71'),
                'methods'    => array(''),
            ),
            'tokenizer' => array(
                'constants'  => array('', '70', '74')
            ),
            'uopz' => array(
                'constants'  => array('2'),
                'functions'  => array('2', '5'),
                'iniEntries' => array('2', '5', '6'),
                'releases'   => array('2', '5', '6'),
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
            'yaml' => array(
                'releases'   => array('', '2'),
            ),
            'zendopcache' => array(
                'functions'  => array('7'),
                'iniEntries' => array('', '7', '71', '74'),
                'releases'   => array('', '7', '71', '74'),
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
            $temp = $this->jsonFileHandler->read($refName, $ext, $major);
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
