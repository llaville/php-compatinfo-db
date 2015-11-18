# Introduction

Main goal is to extract database of this project and maintain it as a separated project.
It can be updated when needed, without relation to php-compatinfo life cycle.

# Status

Currently under development. An alpha version should be available soon, before reaching final milestone 
[5.0.0](https://github.com/llaville/php-compat-info/milestones/5.0.0) of php-compatinfo

# Unit Tests

Each extension (currently 105) supported has its own Test case file.
If you launch all tests, depending of your platform (CPU, memory), you may have sensation 
that PHPUnit do nothing for a long time.

Reason is PHPUnit count all tests before running them. Sebastian Bergmann has opened 
a [ticket](https://github.com/sebastianbergmann/phpunit/issues/10) to solve this situation.

Alternative to this issue, is to used the Phing PHPUnitTask. This is really possible now PHPUnit 
provide a library-only PHAR (see ticket [#1925](https://github.com/sebastianbergmann/phpunit/issues/1925)).

Download the PHPUnit PHAR library at https://phar.phpunit.de/phpunit-library.phar

Invoke the `build.xml` phing script with property `phpunit.pharlocation` set.

E.g:

```
$ php phing-2.12.0.phar -Dphpunit.pharlocation=phpunit-library-5.0.8.phar -f tests/build.xml runtests
```

## 1.0.0-alpha1 (2015-10-31)

With xdebug

```
Results OK. Tests: 6831, Assertions: 7844, Skipped: 314
```

## 1.0.0-alpha2 (2015-11-17)

Without xdebug

```
Results OK. Tests: 6595, Assertions: 7608, Skipped: 317
```
