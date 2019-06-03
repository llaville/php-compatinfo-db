# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

### Added

- Support to PHP 7.1.30

## [2.4.0] - 2019-05-18

### Added

- Support to PHP 7.1.29
- Support to PHP 7.2.18
- Support to PHP 7.3.5

### Changed

- Imagick reference updated to version 3.4.4 (stable)
- Xdebug reference updated to version 2.7.2 (stable)

## [2.3.0] - 2019-04-10

### Added

- Support to PHP 7.3.4
- Support to PHP 7.2.17
- Support to PHP 7.1.28

### Changed

- Igbinary reference updated to version 3.0.1 (stable)
- Mailparse reference updated to version 3.0.3 (stable)
- Redis reference updated to version 4.3.0 (stable)
- Xdebug reference updated to version 2.7.1 (stable)

## [2.2.0] - 2019-03-10

### Added

- Support to PHP 7.3.3
- Support to PHP 7.2.16
- Support to PHP 7.1.27

### Changed

- Xdebug reference updated to version 2.7.0 (stable)

## [2.1.1] - 2019-02-24

### Added

- Do not failed tests related to current installed extensions, when versions referenced are lowest

### Changed

- Uopz reference updated to version 6.0.1 (stable)

## [2.1.0] - 2019-02-13

### Added

- Support to PHP 7.3.2
- Support to PHP 7.2.15

## [2.0.0] - 2019-01-19

### Added

- Support to PHP 7.3.1
- Support to PHP 7.2.14
- Support to PHP 7.1.26
- Support to PHP 5.6.40

### Changed

- `bartlett:db:init` command allow to specify a final DB version to tag rather than temporary package version (2.x-dev@commit) 
- `bartlett:db:init` command does not accept to build a specific extension anymore 
- Amqp reference updated to version 1.9.4 (stable)
- Uopz reference updated to version 5.1.0 (stable)

## [2.0.0RC1] - 2018-12-31

### Added

- Support to PHP 7.3.0

### Changed

- Drop support to PHP 5
- APCu reference updated to version 5.1.6 (stable)
- Lzf reference updated to version 1.6.7 (stable)
- Memcached reference updated to version 3.1.3 (stable)
- Msgpack reference updated to version 2.0.3 (stable)

## [1.39.0] - 2018-12-16

### Added

- Support to PHP 7.2.13
- Support to PHP 7.1.25
- Support to PHP 7.0.33
- Support to PHP 5.6.39

### Changed

- APCu reference updated to version 5.1.5 (stable)
- Redis reference updated to version 4.2.0 (stable)
- Yaml reference updated to version 1.3.2 (stable)

## [1.38.1] - 2018-11-18

### Changed

- Only support PHPUnit v5 and v6 to run unit tests.
- Upgrade and clean some dependencies.

## [1.38.0] - 2018-11-12

### Added

- Support to PHP 7.1.24
- Support to PHP 7.2.12

### Changed

- Ast reference updated to version 1.0.0 (stable)
- Igbinary reference updated to version 2.0.8 (stable)

## [1.37.0] - 2018-10-12

### Added

- Support to PHP 7.1.23
- Support to PHP 7.2.10, 7.2.11
- New reference to Raphf 2.0.0 (stable)

### Changed

- Apcu reference updated to version 5.1.12 (stable)
- Ast reference updated to version 0.1.7 (stable)
- Uopz reference updated to version 5.0.2 (stable)
- Zip reference updated to version 1.15.4 (stable)

### Fixed 

- issue [GH-17](https://github.com/llaville/php-compatinfo-db/issues/17)
- issue [GH-16](https://github.com/llaville/php-compatinfo-db/issues/16)
- issue [GH-15](https://github.com/llaville/php-compatinfo-db/issues/15)
- issue [GH-14](https://github.com/llaville/php-compatinfo-db/issues/14)

## [1.36.0] - 2018-10-06

### Added

- Support to PHP 5.6.38
- Support to PHP 7.0.32
- Support to PHP 7.1.22

### Changed

- Memcached reference updated to version 3.0.4 (stable)
- Redis reference updated to version 4.1.1 (stable)

## [1.35.0] - 2018-08-28

### Added

- Support to PHP 7.1.21

### Changed

- Xdebug reference updated to version 2.6.1 (stable)

## [1.34.0] - 2018-07-20

### Added

- Support to PHP 7.1.20
- Support to PHP 7.0.31
- Support to PHP 5.6.37

## [1.33.0] - 2018-07-05

### Added

- Support to PHP 7.1.18 and 7.1.19

### Changed

- Igbinary reference updated to version 2.0.7 (stable)
- Stomp reference updated to version 2.0.2 (stable)
- Varnish reference updated to version 1.2.4 (stable)

## [1.32.0] - 2018-04-26

### Added

- Support to PHP 7.1.17
- Support to PHP 7.0.30
- Support to PHP 5.6.36

## [1.31.0] - 2018-04-04

### Added

- Support to PHP 7.1.16
- Support to PHP 7.0.29
- Support to PHP 5.6.35

### Changed

- Varnish reference updated to version 1.2.3 (stable)

## [1.30.0] - 2018-03-01

### Added

- Support to PHP 7.1.15
- Support to PHP 7.0.28
- Support to PHP 5.6.34

### Changed

- APCu reference updated to version 5.1.10 (stable)

## [1.29.0] - 2018-02-01

### Added

- Support to PHP 7.1.14

### Changed

- Xdebug reference updated to version 2.6.0 (stable)

### Fixed

- issue [GH-241](https://github.com/llaville/php-compat-info/issues/241) about imagepng (declared in php-compat-info project)
- issue [GH-12](https://github.com/llaville/php-compatinfo-db/issues/12) $escape_char param of fputcsv() requires PHP = 5.5.4

## [1.28.0] - 2018-01-08

### Added

- Support to PHP 5.6.33

## [1.27.0] - 2018-01-04

### Added

- Support to PHP 7.1.13
- Support to PHP 7.0.27

### Changed

- APCu reference updated to version 5.1.9 (stable)
- Redis reference updated to version 3.1.6 (stable)
- Zip reference updated to version 1.15.2 (stable)

## [1.26.0] - 2017-11-24

### Added

- Support to PHP 7.1.12
- Support to PHP 7.0.26

### Changed

- Igbinary reference updated to version 2.0.5 (stable)

## [1.25.0] - 2017-10-30

### Added

- Support to PHP 7.1.11
- Support to PHP 7.0.25
- Support to PHP 5.6.32

### Changed

- Amqp reference updated to version 1.9.3 (stable)
- Ast reference updated to version 0.1.6 (stable)

## [1.24.0] - 2017-10-02

### Added

- Support to PHP 7.1.10
- Support to PHP 7.0.24

### Changed

- Xdebug reference updated to version 2.5.5 (stable)

## [1.24.0RC1] - 2017-09-23

### Added

- Support to PHP 7.1.9
- Support to PHP 7.0.23
- Support to PHP 7.0.22

### Changed

- APCu reference updated to version 5.1.8 (stable)
- APCu reference updated to version 4.0.11 (stable)
- Filter reference updated
- Ftp reference updated
- GD reference updated
- Geoip reference updated
- Gmp reference updated
- Imagick reference updated to version 3.4.3 (stable)
- Intl reference updated
- Ldap reference updated
- Mailparse reference updated
- Mbstring reference updated
- Mongo reference updated to version 1.6.16 (stable)
- Msgpack reference updated
- Mysqli reference updated
- OAuth reference updated
- Openssl reference updated
- Pgsql reference updated
- SPL reference updated
- Rar reference updated to version 4.0.0 (stable)
- Redis reference updated to version 3.1.3 (stable)
- Session reference updated
- Soap reference updated
- Sockets reference updated
- Solr reference updated
- Sphinx reference updated
- SQLite3 reference updated
- SSH2 reference updated
- Sync reference updated
- Tidy reference updated
- Varnish reference updated
- Xdebug reference updated to version 2.5.4 (stable)
- Xsl reference updated
- Zend OPCache reference updated

## [1.23.0] - 2017-07-17

### Added

- Support to PHP 7.0.21
- Support to PHP 5.6.31
- New `db:build:ext` command to generate a draft (json format) of each components in one extension.
- New `db:list` command to see what are extensions supported by the database.
- New `ExtensionFactory::getExtensions()` method to retrieve all extensions informations (status/versions)
- New `db:show` command to see details of extensions supported by the database.

### Changed

- Amqp reference updated to version 1.9.1 (stable)
- Lzf reference updated to version 1.6.6 (stable)
- Redis reference updated to version 3.1.2 (stable)
- Ssh2 reference updated to version 1.1 (alpha)
- Stomp reference updated to version 2.0.1 (stable)
- Zip reference updated to version 1.15.1 (stable)
- DataBase `compatinfo.sqlite` is copied in same directory (<user>\.bartlett) for both phar and non phar versions.
- Console `db:backup` command did not used anymore the system temporary folder to save DB backup files (save in same folder as DB)

## [1.22.0] - 2017-06-10

### Added

- Support to PHP 7.0.20

## [1.21.0] - 2017-06-08

### Added

- Support to PHP 7.0.19

## [1.20.0] - 2017-04-14

### Added

- Support to PHP 7.0.18

### Fixed

[security: uses db from known path](https://github.com/llaville/php-compatinfo-db/issues/9)

## [1.19.0] - 2017-03-16

### Added

- Support to PHP 7.0.17

### Changed

- Xdebug reference updated to version 2.5.1 (stable)

### Fixed

[security: uses db from known path](https://github.com/llaville/php-compatinfo-db/issues/9)

## [1.18.0] - 2017-02-24

### Added

- Support to PHP 7.0.16

## [1.17.0] - 2017-01-23

### Added

- Support to PHP 7.0.15
- Support to PHP 5.6.30

### Changed

- Igbinary reference updated to version 2.0.1 (stable)

## [1.16.0] - 2016-12-15

### Added

- Support to PHP 7.0.14
- Support to PHP 5.6.29

### Changed

- Xdebug reference updated to version 2.5.0 (stable)

## [1.15.0] - 2016-11-22

### Added

- Support to PHP 7.0.13
- Support to PHP 5.6.28

## [1.14.0] - 2016-10-15

### Added

- Support to PHP 7.0.12
- Support to PHP 5.6.27

## [1.13.0] - 2016-10-03

### Added

- Support to PHP 7.0.11
- Support to PHP 5.6.26

### Fixed

- curl reference with libCurl dependency (see https://github.com/llaville/php-compatinfo-db/issues/7)

## [1.12.0] - 2016-09-26

### Added

- Support to PHP 7.0.10
- Support to PHP 5.6.25

### Changed

- Yaml reference updated to version 1.3.0 (stable)

## [1.11.0] - 2016-07-25

### Added

- Support to PHP 7.0.9
- Support to PHP 5.6.24
- Support to PHP 5.5.38

## [1.10.0] - 2016-07-04

### Added

- Support to PHP 7.0.8
- Support to PHP 5.6.23
- Support to PHP 5.5.37

## [1.9.0] - 2016-05-27

### Added

- Support to PHP 7.0.7
- Support to PHP 5.6.22
- Support to PHP 5.5.36

## [1.8.1] - 2016-05-03

### Fixed

- Rollback to Imagick references 3.4.1

## [1.8.0] - 2016-05-02

### Added

- Support to PHP 7.0.6
- Support to PHP 5.6.21
- Support to PHP 5.5.35

### Changed

- Mongo reference updated to version 1.6.14 (stable)

## [1.7.0] - 2016-04-11

### Added

- Support to PHP 7.0.5
- Support to PHP 5.6.20
- Support to PHP 5.5.34

### Changed

- Imagick reference updated to version 3.4.1 (stable)
- Lzf reference updated to version 1.6.5 (stable)
- Mongo reference updated to version 1.6.13 (stable)

## [1.6.0] - 2016-03-05

### Added

- Support to PHP 7.0.4
- Support to PHP 5.6.19
- Support to PHP 5.5.33

### Changed

- Pthreads reference updated to version 3.1.6 (stable)
- Xdebug reference updated to version 2.4.0 (stable)
- Zip reference updated to version 1.13.2 (stable)

## [1.5.0] - 2016-02-05

### Added

- Support to PHP 7.0.3
- Support to PHP 5.6.18
- Support to PHP 5.5.32

### Changed

- Xdebug reference updated to version 2.4.0RC4 (beta)

## [1.4.0] - 2016-01-09

### Added

- Support to PHP 7.0.2
- Support to PHP 5.6.17
- Support to PHP 5.5.31

### Changed

- Stomp reference updated to version 1.0.9 (stable)

### Fixed

- [Issue 3](https://github.com/llaville/php-compatinfo-db/issues/3) : Json Failed test

## [1.3.0] - 2015-12-17

### Added

- Support to PHP 7.0.1

### Changed

- APCu reference updated to version 4.0.10 (stable)
- Http reference updated to version 2.5.5 (stable)
- Xdebug reference updated to version 2.4.0RC3 (beta)

## [1.2.0] - 2015-12-04

### Added

- Support to PHP 7.0.0

### Changed

- Pthreads reference updated to version 3.1.4 (stable)
- Solr reference updated to version 2.3.0 (stable)
- Xdebug reference updated to version 2.4.0RC2 (beta)

## [1.1.0] - 2015-11-26

### Added

- Support to PHP 5.6.16
- Support to PHP 7.0.0RC8

### Changed

- Mongo reference updated to version 1.6.12 (stable)
- Pthreads reference updated to version 3.1.3 (stable)

## [1.0.0] - 2015-11-24

### Added

- Support to PHP 7.0.0RC7
