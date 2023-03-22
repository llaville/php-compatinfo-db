<!-- markdownlint-disable MD013 -->
# Getting started

## Requirements

* PHP 8.0 or greater
* ext-json
* ext-pcre
* ext-pdo
* ext-spl
* PHPUnit 9 or greater (if you want to run unit tests)

## Installation

### With Composer

Install the PHP CompatInfoDB with [Composer](https://getcomposer.org/).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```shell
composer require bartlett/php-compatinfo-db ^5
```

### With Git

The PHP CompatInfoDB can be directly used from [GitHub](https://github.com/llaville/php-compatinfo-db.git)
by cloning the repository into a directory of your choice.

```shell
git clone https://github.com/llaville/php-compatinfo-db.git
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

## Quick start

Since version 4.0, CompatInfoDB introduced the new **auto-diagnose** feature.
That means, if you try to attempt to read the database (by `db:list` or `db:show` commands)
without it was initialized (schema created and data loaded), the `diagnose` command is run automagically and prints its results.
