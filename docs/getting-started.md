<!-- markdownlint-disable MD013 -->
# Getting started

## Requirements

* PHP 7.2 or greater
* ext-json
* ext-pcre
* ext-pdo
* ext-spl
* PHPUnit 8 or greater (if you want to run unit tests)

## Installation

### With Composer

Install the PHP CompatInfoDB with [Composer](https://getcomposer.org/).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```shell
composer require bartlett/php-compatinfo-db
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
# DATABASE_URL="sqlite:///${HOME}/.cache/bartlett/compatinfo-db.sqlite"

# to use postgresql:
# DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=11&charset=utf8"
```

If you change database connection, you have to run following commands:

* `vendor/bin/doctrine orm:schema-tool:create`
* `bin/compatinfo-db db:init`

At dependencies installation, Composer use the sqlite back-end. You need to set up in your environment the `DATABASE_URL` variable.
