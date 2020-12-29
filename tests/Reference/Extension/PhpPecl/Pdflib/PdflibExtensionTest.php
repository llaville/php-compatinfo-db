<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, pdflib extension Reference
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Pdflib;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class PdflibExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // functions only available if PDFLIB_MAJORVERSION >= 8
        // so not available with pdflib-lite free library
        self::$optionalfunctions = array(
            'pdf_add_path_point',
            'pdf_add_portfolio_file',
            'pdf_add_portfolio_folder',
            'pdf_align',
            'pdf_begin_dpart',
            'pdf_begin_glyph_ext',
            'pdf_begin_pattern_ext',
            'pdf_circular_arc',
            'pdf_close_font',
            'pdf_close_graphics',
            'pdf_convert_to_unicode',
            'pdf_delete_path',
            'pdf_draw_path',
            'pdf_ellipse',
            'pdf_elliptical_arc',
            'pdf_end_dpart',
            'pdf_end_template_ext',
            'pdf_fill_graphicsblock',
            'pdf_fit_graphics',
            'pdf_get_option',
            'pdf_get_string',
            'pdf_info_graphics',
            'pdf_info_image',
            'pdf_info_path',
            'pdf_info_pdi_page',
            'pdf_info_pvf',
            'pdf_load_asset',
            'pdf_load_graphics',
            'pdf_poca_delete',
            'pdf_poca_insert',
            'pdf_poca_new',
            'pdf_poca_remove',
            'pdf_set_graphics_option',
            'pdf_set_option',
            'pdf_set_text_option',
            'pdf_utf16_to_utf32',
            'pdf_utf8_to_utf16',
            'pdf_utf8_to_utf32',
            'pdf_utf32_to_utf8',
        );

        parent::setUpBeforeClass();
    }
}
