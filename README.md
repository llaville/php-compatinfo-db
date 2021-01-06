# PHP CompatInfoDB

| Stable |
|:------:|
| [![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/php-compatinfo-db)](https://packagist.org/packages/bartlett/php-compatinfo-db) |
| [![Minimum PHP Version)](https://img.shields.io/packagist/php-v/bartlett/php-compatinfo-db)](https://php.net/) |
| [![Branch Master](https://img.shields.io/badge/branch-master-blue)](https://github.com/llaville/php-compatinfo-db) |
| ![Tests](https://github.com/llaville/php-compatinfo-db/workflows/Tests/badge.svg) |

Main goal of this project is to provide a standalone database that references
all functions, constants, classes, interfaces on PHP standard distribution and about 100 extensions.

This database is currently only support by its initial project [php-compatinfo](https://github.com/llaville/php-compat-info)

## Features

* a Symfony console application to handle data (json files) of the SQL database is provide on CLI API

More than 100 extensions (PHP standard distribution, but also PECL) are currently support :

* reference all functions
* reference all constants
* reference all classes
* reference all classes constants
* reference all interfaces
* reference all methods
* reference all ini entries
* reference all releases

## Supported Versions

| Version | Release    | Module            | PHP 5.2 | PHP 5.3 | PHP 5.4 | PHP 5.5 | PHP 5.6 | PHP 7.0 | PHP 7.1 | PHP 7.2 | PHP 7.3 | PHP 7.4 | PHP 8.0 |
|---------|------------|-------------------|---------|---------|---------|---------|---------|---------|---------|---------|---------|---------|---------|
| 3.0.1   | 2021-01-06 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.34 |  7.3.25 |  7.4.13 |  8.0.0  |
|         |            | xdebug 3.0.2      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yac 2.3.0         |         |         |         |         |         |         |         |         |         |         |         |
| 3.0.0   | 2020-12-29 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.34 |  7.3.25 |  7.4.13 |  8.0.0  |
|         |            | apcu 5.1.19       |         |         |         |         |         |         |         |         |  7.3.24 |  7.4.12 |         |
|         |            | igbinary 3.2.1    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | jsmin 3.0.0       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | memcache 8.0      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | msgpack 2.1.2     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | oci8 3.0.1        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | pdflib 4.1.4      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | raphf 2.0.1       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | rar 4.2.0         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 5.3.2       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | uopz 6.1.2        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 3.0.1      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xhprof 2.2.3      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yac 2.2.1         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yaml 2.2.1        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.19.2        |         |         |         |         |         |         |         |         |         |         |         |
| 2.19.0  | 2020-10-03 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.34 |  7.3.23 |  7.4.11 |    x    |
|         |            | mailparse 3.1.1   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | oauth 2.0.7       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.8      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.19.1        |         |         |         |         |         |         |         |         |         |         |         |
| 2.18.0  | 2020-09-13 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.33 |  7.3.22 |  7.4.10 |    x    |
|         |            | ast 1.0.10        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | igbinary 3.1.5    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | oauth 2.0.6       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | solr 2.5.1        |         |         |         |         |         |         |         |         |         |         |         |
| 2.17.0  | 2020-08-15 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.33 |  7.3.21 |  7.4.9  |    x    |
|         |            | ast 1.0.8         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | igbinary 3.1.4    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | msgpack 2.1.1     |         |         |         |         |         |         |         |         |         |         |         |
| 2.16.0  | 2020-07-11 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.32 |  7.3.20 |  7.4.8  |    x    |
|         |            | redis 5.3.1       |         |         |         |         |         |         |         |         |         |         |         |
| 2.15.0  | 2020-06-30 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.31 |  7.3.19 |  7.4.7  |    x    |
|         |            | oci8 2.2.0        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.6      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.19.0        |         |         |         |         |         |         |         |         |         |         |         |
| 2.14.0  | 2020-05-17 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.31 |  7.3.18 |  7.4.6  |    x    |
| 2.13.0  | 2020-05-10 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.30 |  7.3.17 |  7.4.5  |    x    |
|         |            | amqp 1.10.0       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | lzf 1.6.8         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 5.2.2       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.5      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yaml 2.1.0        |         |         |         |         |         |         |         |         |         |         |         |
| 2.12.0  | 2020-03-21 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.29 |  7.3.16 |  7.4.4  |    x    |
|         |            | zip 1.18.2        |         |         |         |         |         |         |         |         |         |         |         |
| 2.11.0  | 2020-03-20 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.28 |  7.3.15 |  7.4.3  |    x    |
|         |            | msgpack 2.1.0     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | oauth 2.0.5       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 5.2.1       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ssh2 1.2          |         |         |         |         |         |         |         |         |         |         |         |
|         |            | Uploadprogress    |         |         |         |         |         |         |         |         |         |         |         |
|         |            |     1.1.3         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.2      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yaml 2.0.4        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.18.1        |         |         |         |         |         |         |         |         |         |         |         |
| 2.10.0  | 2020-01-27 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.27 |  7.3.14 |  7.4.2  |    x    |
|         |            | igbinary 3.1.2    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | svn 2.0.3         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | Uploadprogress    |         |         |         |         |         |         |         |         |         |         |         |
|         |            |     1.1.2         |         |         |         |         |         |         |         |         |         |         |         |
| 2.9.0   | 2020-01-22 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.26 |  7.3.13 |  7.4.1  |    x    |
|         |            | igbinary 3.1.1    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 5.1.1       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.1      |         |         |         |         |         |         |         |         |         |         |         |
| 2.8.0   | 2019-12-30 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.26 |  7.3.13 |  7.4.1  |    x    |
|         |            | igbinary 3.1.0    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | memcached 3.1.5   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.9.0      |         |         |         |         |         |         |         |         |         |         |         |
| 2.7.0   | 2019-12-02 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.25 |  7.3.12 |  7.4.0  |    x    |
|         |            |                   |         |         |         |         |         |         |         |  7.2.24 |  7.3.11 |         |         |
|         |            |                   |         |         |         |         |         |         |         |  7.2.23 |  7.3.10 |         |         |
|         |            |                   |         |         |         |         |         |         |         |  7.2.22 |  7.3.9  |         |         |
|         |            |                   |         |         |         |         |         |         |         |  7.2.21 |  7.3.8  |         |         |
|         |            | solr 2.5.0        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.8.0      |         |         |         |         |         |         |         |         |         |         |         |
| 2.6.0   | 2019-07-25 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.20 |  7.3.7  |    x    |    x    |
| 2.5.0   | 2019-06-15 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.30 |  7.2.19 |  7.3.6  |    x    |    x    |
| 2.4.0   | 2019-05-18 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.29 |  7.2.18 |  7.3.5  |    x    |    x    |
|         |            | imagick 3.4.4     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.7.2      |         |         |         |         |         |         |         |         |         |         |         |
| 2.3.0   | 2019-04-10 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.28 |  7.2.17 |  7.3.4  |    x    |    x    |
|         |            | igbinary 3.0.1    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mailparse 3.0.3   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 4.3.0       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.7.1      |         |         |         |         |         |         |         |         |         |         |         |
| 2.2.0   | 2019-03-10 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.27 |  7.2.16 |  7.3.3  |    x    |    x    |
|         |            | xdebug 2.7.0      |         |         |         |         |         |         |         |         |         |         |         |
| 2.1.0   | 2019-02-13 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.26 |  7.2.15 |  7.3.2  |    x    |    x    |
| 2.0.0   | 2019-01-19 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.40  |  7.0.33 |  7.1.26 |  7.2.14 |  7.3.1  |    x    |    x    |
|         |            | amqp 1.9.4        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | uopz 5.1.0        |         |         |         |         |         |         |         |         |         |         |         |
| 2.0.0   | 2018-12-31 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.39  |  7.0.33 |  7.1.25 |  7.2.13 |  7.3.0  |    x    |    x    |
|   RC1   |            |                   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | apcu 5.1.16       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | lzf 1.6.7         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | memcached 3.1.3   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | msgpack 2.0.3     |         |         |         |         |         |         |         |         |         |         |         |
| 1.39.0  | 2018-12-16 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.39  |  7.0.33 |  7.1.25 |  7.2.13 |    x    |    x    |    x    |
|         |            | apcu 5.1.15       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 4.2.0       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | yaml 1.3.2        |         |         |         |         |         |         |         |         |         |         |         |
| 1.38.0  | 2018-11-12 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.38  |  7.0.32 |  7.1.24 |  7.2.12 |    x    |    x    |    x    |
|         |            | ast 1.0.0         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | igbinary 2.0.8    |         |         |         |         |         |         |         |         |         |         |         |
| 1.37.0  | 2018-10-12 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.38  |  7.0.32 |  7.1.23 |  7.2.11 |    x    |    x    |    x    |
|         |            |                   |         |         |         |         |         |         |         |  7.2.10 |         |         |         |
|         |            | apcu 5.1.12       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ast 0.1.7         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | raphf 2.0.0       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | uopz 5.0.2        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.15.4        |         |         |         |         |         |         |         |         |         |         |         |
| 1.36.0  | 2018-10-06 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.38  |  7.0.32 |  7.1.22 |    x    |    x    |    x    |    x    |
|         |            | memcached 3.0.4   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 4.1.1       |         |         |         |         |         |         |         |         |         |         |         |
| 1.35.0  | 2018-08-28 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.37  |  7.0.31 |  7.1.21 |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.6.1      |         |         |         |         |         |         |         |         |         |         |         |
| 1.34.0  | 2018-07-20 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.37  |  7.0.31 |  7.1.20 |    x    |    x    |    x    |    x    |
| 1.33.0  | 2018-07-05 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.36  |  7.0.30 |  7.1.19 |    x    |    x    |    x    |    x    |
|         |            |                   |         |         |         |         |         |         |  7.1.18 |         |         |         |         |
|         |            | igbinary 2.0.7    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | stomp 2.0.2       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | varnish 1.2.4     |         |         |         |         |         |         |         |         |         |         |         |
| 1.32.0  | 2018-04-26 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.36  |  7.0.30 |  7.1.17 |    x    |    x    |    x    |    x    |
| 1.31.0  | 2018-04-04 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.35  |  7.0.29 |  7.1.16 |    x    |    x    |    x    |    x    |
|         |            | varnish 1.2.3     |         |         |         |         |         |         |         |         |         |         |         |
| 1.30.0  | 2018-03-01 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.34  |  7.0.28 |  7.1.15 |    x    |    x    |    x    |    x    |
|         |            | apcu 5.1.10       |         |         |         |         |         |         |         |         |         |         |         |
| 1.29.0  | 2018-02-01 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.33  |  7.0.27 |  7.1.14 |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.6.0      |         |         |         |         |         |         |         |         |         |         |         |
| 1.28.0  | 2018-01-08 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.33  |  7.0.27 |  7.1.13 |    x    |    x    |    x    |    x    |
| 1.27.0  | 2018-01-04 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.32  |  7.0.27 |  7.1.13 |    x    |    x    |    x    |    x    |
|         |            | apcu 5.1.9        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 3.1.6       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.15.2        |         |         |         |         |         |         |         |         |         |         |         |
| 1.26.0  | 2017-11-24 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.32  |  7.0.26 |  7.1.12 |    x    |    x    |    x    |    x    |
|         |            | igbinary 2.0.5    |         |         |         |         |         |         |         |         |         |         |         |
| 1.25.0  | 2017-10-30 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.32  |  7.0.25 |  7.1.11 |    x    |    x    |    x    |    x    |
|         |            | amqp 1.9.3        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ast 0.1.6         |         |         |         |         |         |         |         |         |         |         |         |
| 1.24.0  | 2017-10-02 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.31  |  7.0.24 |  7.1.10 |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.5.5      |         |         |         |         |         |         |         |         |         |         |         |
| 1.24.0  | 2017-09-23 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.31  |  7.0.23 |  7.1.9  |    x    |    x    |    x    |    x    |
|   RC1   |            |                   |         |         |         |         |         |  7.0.22 |         |         |         |         |         |
|         |            | apcu 5.1.8        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | apcu 4.0.11       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | filter            |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ftp               |         |         |         |         |         |         |         |         |         |         |         |
|         |            | gd                |         |         |         |         |         |         |         |         |         |         |         |
|         |            | geoip 1.1.1       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | gmp               |         |         |         |         |         |         |         |         |         |         |         |
|         |            | imagick 3.4.3     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | intl              |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ldap              |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mailparse 3.0.2   |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mbstring          |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mongo 1.6.16      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | msgpack 2.0.2     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mysqli            |         |         |         |         |         |         |         |         |         |         |         |
|         |            | oauth 2.0.2       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | opcache           |         |         |         |         |         |         |         |         |         |         |         |
|         |            | openssl           |         |         |         |         |         |         |         |         |         |         |         |
|         |            | pgsql             |         |         |         |         |         |         |         |         |         |         |         |
|         |            | rar 4.0.0         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 3.1.3       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | session           |         |         |         |         |         |         |         |         |         |         |         |
|         |            | spl               |         |         |         |         |         |         |         |         |         |         |         |
|         |            | soap              |         |         |         |         |         |         |         |         |         |         |         |
|         |            | sockets           |         |         |         |         |         |         |         |         |         |         |         |
|         |            | sphinx 1.3.3      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | sqlite3           |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ssh2 1.1.2        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | sync 1.1.1        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | tidy              |         |         |         |         |         |         |         |         |         |         |         |
|         |            | varnish 1.2.2     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.5.4      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xsl               |         |         |         |         |         |         |         |         |         |         |         |
| 1.23.0  | 2017-07-17 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.31  |  7.0.21 |    x    |    x    |    x    |    x    |    x    |
|         |            | amqp 1.9.1        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | lzf 1.6.6         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | redis 3.1.2       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | ssh2 1.1          |         |         |         |         |         |         |         |         |         |         |         |
|         |            | stomp 2.0.1       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.15.1        |         |         |         |         |         |         |         |         |         |         |         |
| 1.22.0  | 2017-06-10 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.20 |    x    |    x    |    x    |    x    |    x    |
| 1.21.0  | 2017-06-08 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.19 |    x    |    x    |    x    |    x    |    x    |
| 1.20.0  | 2017-04-14 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.18 |    x    |    x    |    x    |    x    |    x    |
| 1.19.0  | 2017-03-16 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.17 |    x    |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.5.1      |         |         |         |         |         |         |         |         |         |         |         |
| 1.18.0  | 2017-02-23 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.16 |    x    |    x    |    x    |    x    |    x    |
| 1.17.0  | 2017-01-23 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.30  |  7.0.15 |    x    |    x    |    x    |    x    |    x    |
|         |            | igbinary 2.0.1    |         |         |         |         |         |         |         |         |         |         |         |
| 1.16.0  | 2016-12-15 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.29  |  7.0.14 |    x    |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.5.0      |         |         |         |         |         |         |         |         |         |         |         |
| 1.15.0  | 2016-11-22 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.28  |  7.0.13 |    x    |    x    |    x    |    x    |    x    |
| 1.14.0  | 2016-10-15 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.27  |  7.0.12 |    x    |    x    |    x    |    x    |    x    |
| 1.13.0  | 2016-10-03 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.26  |  7.0.11 |    x    |    x    |    x    |    x    |    x    |
| 1.12.0  | 2016-09-26 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.25  |  7.0.10 |    x    |    x    |    x    |    x    |    x    |
|         |            | yaml 1.3.0        |         |         |         |         |         |         |         |         |         |         |         |
| 1.11.0  | 2016-07-25 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.38  | 5.6.24  |  7.0.9  |    x    |    x    |    x    |    x    |    x    |
| 1.10.0  | 2016-07-04 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.37  | 5.6.23  |  7.0.8  |    x    |    x    |    x    |    x    |    x    |
| 1.9.0   | 2016-05-27 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.36  | 5.6.22  |  7.0.7  |    x    |    x    |    x    |    x    |    x    |
| 1.8.0   | 2016-05-02 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.35  | 5.6.21  |  7.0.6  |    x    |    x    |    x    |    x    |    x    |
|         |            | mongo 1.6.14      |         |         |         |         |         |         |         |         |         |         |         |
| 1.7.0   | 2016-04-11 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.34  | 5.6.20  |  7.0.5  |    x    |    x    |    x    |    x    |    x    |
|         |            | imagick 3.4.1     |         |         |         |         |         |         |         |         |         |         |         |
|         |            | lzf 1.6.5         |         |         |         |         |         |         |         |         |         |         |         |
|         |            | mongo 1.6.13      |         |         |         |         |         |         |         |         |         |         |         |
| 1.6.0   | 2016-03-05 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.33  | 5.6.19  |  7.0.4  |    x    |    x    |    x    |    x    |    x    |
|         |            | pthreads 3.1.6    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.4.0      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | zip 1.13.2        |         |         |         |         |         |         |         |         |         |         |         |
| 1.5.0   | 2016-02-05 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.32  | 5.6.18  |  7.0.3  |    x    |    x    |    x    |    x    |    x    |
|         |            | xdebug 2.4.0RC4   |         |         |         |         |         |         |         |         |         |         |         |
| 1.4.0   | 2016-01-09 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.31  | 5.6.17  |  7.0.2  |    x    |    x    |    x    |    x    |    x    |
|         |            | stomp 1.0.9       |         |         |         |         |         |         |         |         |         |         |         |
| 1.3.0   | 2015-12-17 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.30  | 5.6.16  |  7.0.1  |    x    |    x    |    x    |    x    |    x    |
|         |            | apcu 4.0.10       |         |         |         |         |         |         |         |         |         |         |         |
|         |            | http 2.5.5        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.4.0RC3   |         |         |         |         |         |         |         |         |         |         |         |
| 1.2.0   | 2015-12-04 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.30  | 5.6.16  |  7.0.0  |    x    |    x    |    x    |    x    |    x    |
|         |            | pthreads 3.1.4    |         |         |         |         |         |         |         |         |         |         |         |
|         |            | solr 2.3.0        |         |         |         |         |         |         |         |         |         |         |         |
|         |            | xdebug 2.4.0RC2   |         |         |         |         |         |         |         |         |         |         |         |
| 1.1.0   | 2015-11-26 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.30  | 5.6.16  |  RC8    |    x    |    x    |    x    |    x    |    x    |
|         |            | mongo 1.6.12      |         |         |         |         |         |         |         |         |         |         |         |
|         |            | pthreads 3.1.3    |         |         |         |         |         |         |         |         |         |         |         |
| 1.0.0   | 2015-11-24 |                   | 5.2.17  | 5.3.29  | 5.4.45  | 5.5.30  | 5.6.15  |  RC7    |    x    |    x    |    x    |    x    |    x    |


Currently, 108 extensions referenced in the database.

For future versions, see the `CHANGELOG-3.x.md` file.

## Examples

See `examples/useExtensionFactory.php` script to learn how to access to information in database.

## Requirements

* PHP 7.2 or greater
* PHPUnit 8 or greater (if you want to run unit tests)

## Installation

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/php-compatinfo-db
```

## Configuring the Database

The database connection information is stored as an environment variable called `DATABASE_URL`.

```
# to use mysql:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

# to use mariadb:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"

# to use sqlite:
# DATABASE_URL="sqlite:///${HOME}/.cache/bartlett/compatinfo-db.sqlite"

# to use postgresql:
# DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8"
```

If you change database connection, you have to run following commands:
- `vendor/bin/doctrine orm:schema-tool:create`
- `bin/compatinfo-db db:init`

At dependencies installation, Composer use the sqlite back-end. You need to set up in your environment the `DATABASE_URL` variable.

## Contributors

* Laurent Laville (Lead Developer)
* Remi Collet (contributor on many extensions and unit tests)

[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/0)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/0)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/1)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/1)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/2)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/2)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/3)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/3)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/4)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/4)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/5)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/5)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/6)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/6)
[![](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/images/7)](https://sourcerer.io/fame/llaville/llaville/php-compatinfo-db/links/7)

## License

This project is license under the BSD-3-Clause License - see the [LICENSE](https://github.com/llaville/php-compatinfo-db/blob/master/LICENSE) file for details
