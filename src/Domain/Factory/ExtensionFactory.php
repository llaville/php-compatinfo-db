<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Domain\Factory;

use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Domain\ValueObject\Extension;

/**
 * PHP Modules Bundled
 *
 * enchant
 * @link https://www.php.net/manual/en/book.enchant.php
 * @link https://github.com/php/php-src/tree/master/ext/enchant
 * @link https://github.com/php/php-src/commit/fb0902143291c8b605997a6b2a8f8717289a44d1
 *       Bump enchant extension version to PHP release version since PHP 7.0.26RC1
 *
 * iconv
 * @link https://github.com/php/php-src/tree/master/ext/iconv
 * @link https://github.com/php/php-src/commit/2d78023244eaa5ec60b1325e530150394d625fa8#diff-67e0980e95a854ac032cd5d8450273ecfcf17cd5882fbc0133e32a4a508df04b
 *       Bump iconv extension version to PHP release version since PHP 7.0.0alpha1
 *
 * intl
 * @link https://github.com/php/php-src/tree/master/ext/intl
 * @link https://github.com/php/php-src/commit/b1767d8a5625d7347e500e1230cf6c6c66d111ad
 *       Bump intl extension version to PHP release version since PHP 7.0.0alpha2
 *
 * imagick
 * @link http://pecl.php.net/package/imagick
 * @link https://github.com/Imagick/imagick
 *
 * libxml
 * @link https://github.com/php/php-src/tree/master/ext/libxml
 * @link https://github.com/php/php-src/commit/2d78023244eaa5ec60b1325e530150394d625fa8#diff-3c2cd6479b2be10875c7be2f2fcce5263d5efc342062ed6632a9a0354c7bd19b
 *       Bump libxml extension version to PHP release version since PHP 7.0.0alpha1
 *
 * openssl
 * @link https://github.com/php/php-src/tree/master/ext/openssl
 * @link https://github.com/php/php-src/commit/19360f386ec3e78f7f57f9e943569410dc2f718f#diff-02c614291d4b724f93c9af4e4fc5b8db0dd4abc74ec497b6ee82dea4464c1b7c
 *       Bump openssl extension version to PHP release version since PHP 7.0.0alpha1
 *
 * pcre
 * @link https://github.com/php/php-src/tree/master/ext/pcre
 * @link https://github.com/php/php-src/commit/19360f386ec3e78f7f57f9e943569410dc2f718f#diff-af366ab72244ef61c874d65ed45165d844a6e1728c0e4dbb12554391a6a3f9e1
 *       Bump pcre extension version to PHP release version since PHP 7.0.0alpha1
 *
 * xsl
 * @link https://www.php.net/manual/en/book.xsl.php
 * @link https://github.com/php/php-src/commit/663074b6b1fa4534fbbb65462aeef40f2c983ad5#diff-275f3927808248789ea4e8a508f11bb286e1bc3a51f407c23771fa7fba6e0b93
 *       Bump xsl extension version to PHP release version since PHP 7.0.0alpha1
 *
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class ExtensionFactory implements ExtensionFactoryInterface
{
    use ExtensionVersionProviderTrait;

    private ExtensionRepository $extensionRepository;

    public function __construct(ExtensionRepository $extensionRepository)
    {
        $this->extensionRepository = $extensionRepository;
    }

    public function create(string $name): ?Extension
    {
        return $this->extensionRepository->getExtensionByName($name);
    }
}
