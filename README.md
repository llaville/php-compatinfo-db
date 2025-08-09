# Documentation

Here are the links to the documentation for versions that are still supported : 

- [PHP CompatInfo DB 5.14](https://llaville.github.io/php-compatinfo-db/5.14/)
- [PHP CompatInfo DB 6.16](https://llaville.github.io/php-compatinfo-db/6.16/)
- [PHP CompatInfo DB 6.17](https://llaville.github.io/php-compatinfo-db/6.17/)
- [PHP CompatInfo DB 6.18](https://llaville.github.io/php-compatinfo-db/6.18/)
- [PHP CompatInfo DB 6.19](https://llaville.github.io/php-compatinfo-db/6.19/)
- [PHP CompatInfo DB 6.20](https://llaville.github.io/php-compatinfo-db/6.20/)
- [PHP CompatInfo DB 6.21](https://llaville.github.io/php-compatinfo-db/6.21/)

Full documentation may be found in `docs` folder into this repository, and may be read online without to do anything else.

As alternative, you may generate a professional static site with [Material for MkDocs][mkdocs-material].

Configuration file `mkdocs.yml` is available and if you have Docker support, 
the documentation site can be simply build with following command:

```shell
docker run --rm -it -u "$(id -u):$(id -g)" -v ${PWD}:/docs squidfunk/mkdocs-material build --verbose
```

[mkdocs-material]: https://github.com/squidfunk/mkdocs-material
