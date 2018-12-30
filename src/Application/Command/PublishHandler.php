<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

class PublishHandler implements CommandHandlerInterface
{
    private $jsonFileHandler;

    public function __construct($jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(PublishCommand $command): void
    {
        $relVersion = $command->relVersion;

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

            $data = $this->jsonFileHandler->read($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName . $major, $ext);
                    throw new \RuntimeException($error);
                }
                $data = [];
            }

            $data[] = [
                'ext_name_fk'   => $extId,
                'rel_version'   => $relVersion,
                'rel_date'      => $command->relDate,
                'rel_state'     => $command->relState,
                'ext_max'       => '',
                'php_min'       => $relVersion,
                'php_max'       => '',
            ];
            $this->jsonFileHandler->write($refName, $ext, $major, $data);
        }
    }
}
