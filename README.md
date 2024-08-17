# Documentation

Here are the links to the documentation for versions that are still supported : 

- [PHP CompatInfo DB 5.14](https://llaville.github.io/php-compatinfo-db/5.14/)
- [PHP CompatInfo DB 6.0](https://llaville.github.io/php-compatinfo-db/6.0/)
- [PHP CompatInfo DB 6.1](https://llaville.github.io/php-compatinfo-db/6.1/)
- [PHP CompatInfo DB 6.2](https://llaville.github.io/php-compatinfo-db/6.2/)
- [PHP CompatInfo DB 6.3](https://llaville.github.io/php-compatinfo-db/6.3/)
- [PHP CompatInfo DB 6.4](https://llaville.github.io/php-compatinfo-db/6.4/)
- [PHP CompatInfo DB 6.5](https://llaville.github.io/php-compatinfo-db/6.5/)
- [PHP CompatInfo DB 6.6](https://llaville.github.io/php-compatinfo-db/6.6/)
- [PHP CompatInfo DB 6.7](https://llaville.github.io/php-compatinfo-db/6.7/)
- [PHP CompatInfo DB 6.8](https://llaville.github.io/php-compatinfo-db/6.8/)
- [PHP CompatInfo DB 6.9](https://llaville.github.io/php-compatinfo-db/6.9/)

Full documentation may be found in `docs` folder into this repository, and may be read online without to do anything else.

As alternative, you may generate a professional static site with [Material for MkDocs][mkdocs-material].

Configuration file `mkdocs.yml` is available and if you have Docker support, 
the documentation site can be simply build with following command:

```shell
docker run --rm -it -u "$(id -u):$(id -g)" -v ${PWD}:/docs squidfunk/mkdocs-material build --verbose
```

[mkdocs-material]: https://github.com/squidfunk/mkdocs-material
