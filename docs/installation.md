<!-- markdownlint-disable MD013 -->
# Installation

1. [Requirements](#requirements)
2. [PHAR](#phar)
3. [Docker](#docker)
4. [Phive](#phive)
5. [Composer](#composer)
6. [Git](#git)
7. [Database](#configuring-the-database)

> **NOTE** Since version 4.0, CompatInfoDB introduced the new **auto-diagnose** feature.
That means, if you try to attempt to read the database (by `db:list` or `db:show` commands)
without it was initialized (schema created and data loaded), the `diagnose` command is run automagically and prints its results.

## Requirements

* PHP 8.1 or greater
* ext-json
* ext-pcre
* ext-pdo
* ext-spl
* PHPUnit 10 or greater (if you want to run unit tests)

## PHAR

The preferred method of installation is to use the CompatInfoDB PHAR version which can be downloaded from the most recent
[Github Release][releases]. This method ensures you will not have any dependency conflict issue.

## Docker

Retrieve official image with [Docker][docker]

```shell
docker pull ghcr.io/llaville/php-compatinfo-db:v6
or
docker pull ghcr.io/llaville/php-compatinfo-db:latest
```

## Phive

You can install application globally with [Phive][phive]

```shell
phive install llaville/php-compatinfo-db --force-accept-unsigned
```

To upgrade global installation of the application use the following command:

```shell
phive update llaville/php-compatinfo-db --force-accept-unsigned
```

You can also install application locally to your project with [Phive][phive] and configuration file `.phive/phars.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phive xmlns="https://phar.io/phive">
    <phar name="llaville/php-compatinfo-db" version="^6.9" copy="false" />
</phive>
```

```shell
phive install --force-accept-unsigned
```

## Composer

The recommended way to install this library is [through composer][composer].
If you don't know yet what is composer, have a look [on introduction][composer-intro].

```shell
composer require bartlett/php-compatinfo-db ^6.9
```

If you cannot install it because of a dependency conflict, or you prefer to install it for your project, we recommend
you to take a look at [bamarni/composer-bin-plugin][bamarni/composer-bin-plugin]. Example:

```shell
composer require --dev bamarni/composer-bin-plugin
composer bin compatinfo-db require --dev bartlett/php-compatinfo-db

vendor/bin/compatinfo-db
```

## Git

The PHP CompatInfoDB can be directly used from [GitHub][github-repo] by cloning the repository into a directory of your choice.

```shell
git clone -b 6.9 https://github.com/llaville/php-compatinfo-db.git
```

## Configuring the Database

The database connection information is stored as an environment variable called `DATABASE_URL`.

```shell
# to use mysql:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

# to use mariadb:
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"

# to use sqlite:
DATABASE_URL="sqlite:///%kernel.cache_dir%/compatinfo-db.sqlite"

# to use postgresql:
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8"
```

After installation, or if you change database connection, you have to run following command(s):

* `bin/compatinfo-db db:create` (creates only the database schema)
* `bin/compatinfo-db db:init` (loads the database from JSON data files)

At first run of CompatInfoDB, `DATABASE_URL` will be set to use default SQLite connection

[releases]: https://github.com/llaville/php-compatinfo-db/releases/
[composer]: https://getcomposer.org
[composer-intro]: http://getcomposer.org/doc/00-intro.md
[bamarni/composer-bin-plugin]: https://github.com/bamarni/composer-bin-plugin
[github-repo]: https://github.com/llaville/php-compatinfo-db.git
[phive]: https://github.com/phar-io/phive
[docker]: https://docs.docker.com/get-docker/
