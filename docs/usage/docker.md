<!-- markdownlint-disable MD013 -->
# Docker CLI

**IMPORTANT** : Docker image with `latest` tag use the PHP 8.1 runtime !

> Please mount your system temporary folder to `/home/appuser/.cache/bartlett` in the container.
>
> **NOTE**: On most Linux distribution, it should be `/tmp`

```shell
docker run --rm -it -v /tmp:/home/appuser/.cache/bartlett ghcr.io/llaville/php-compatinfo-db:latest
```

Then you can run any commands supported by application : `db:*`, `diagnose`, `about`, ...
