<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Imagick;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use Exception;

/**
 * Unit tests for PHP_CompatInfo_Db, imagick extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class ImagickExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$ignoredmethods = [
            'ImagickPixel::clone',
        ];

        self::$optionalmethods = [
            'Imagick::deleteOption',            // if IM_HAVE_IMAGICK_DELETE_OPTION
            'Imagick::getBackgroundColor',      // if IM_HAVE_IMAGICK_GET_BACKGROUND_COLOR
            'Imagick::getImageArtifacts',       // if IM_HAVE_IMAGICK_GET_IMAGE_ARTIFACTS
            'Imagick::getImageKurtosis',        // if IM_HAVE_IMAGICK_GET_IMAGE_KURTOSIS
            'Imagick::getImageMean',            // if IM_HAVE_IMAGICK_GET_IMAGE_MEAN
            'Imagick::getImageRange',           // if IM_HAVE_IMAGICK_GET_IMAGE_RANGE
            'Imagick::getInterpolateMethod',    // if IM_HAVE_IMAGICK_GET_INTERPOLATE_METHOD
            'Imagick::getOptions',              // if IM_HAVE_IMAGICK_GET_OPTIONS
            'Imagick::getOrientation',          // if IM_HAVE_IMAGICK_GET_ORIENTATION
            'Imagick::getResolution',           // if IM_HAVE_IMAGICK_GET_RESOLUTION
            'Imagick::getType',                 // if IM_HAVE_IMAGICK_GET_TYPE
            'Imagick::polynomialImage',         // if IM_HAVE_IMAGICK_POLYNOMIAL_IMAGE
            'Imagick::setDepth',                // if IM_HAVE_IMAGICK_SET_DEPTH
            'Imagick::setExtract',              // if IM_HAVE_IMAGICK_SET_EXTRACT
            'Imagick::setInterpolateMethod',    // if IM_HAVE_IMAGICK_SET_INTERPOLATE_METHOD
            'Imagick::setOrientation',          // if IM_HAVE_IMAGICK_SET_ORIENTATION
        ];

        parent::setUpBeforeClass();
    }
}
