<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Gd;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, gd extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class GdExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = array(
            // Win32 only
            'imagegrabscreen',
            'imagegrabwindow',
            // requires HAVE_GD_WEBP, (Win32 only in PHP 5.4)
            'imagecreatefromwebp',
            'imagewebp',
            // requires HAVE_COLORCLOSESTHWB
            'imagecolorclosesthwb',
            // requires HAVE_LIBT1
            'imagepsbbox',
            'imagepsencodefont',
            'imagepsextendfont',
            'imagepsfreefont',
            'imagepsloadfont',
            'imagepsslantfont',
            'imagepstext',
            // requires HAVE_GD_XPM (linux only)
            'imagecreatefromxpm',
            // requires HAVE_GD_TGA
            'imagecreatefromtga',
            // requires HAVE_GD_AVIF (libavif >= 0.8.2)
            'imagecreatefromavif',
            'imageavif',
        );
        if (defined('GD_BUNDLED') && ! GD_BUNDLED) {
            self::$optionalfunctions[] = 'imageantialias';
        }

        self::$optionalconstants = [
            // requires libwebp >= 0.2.0
            'IMG_WEBP_LOSSLESS',
        ];
        parent::setUpBeforeClass();
    }
}
