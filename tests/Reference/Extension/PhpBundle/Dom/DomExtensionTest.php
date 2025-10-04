<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Dom;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, dom extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class DomExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        self::$optionalclasses = [
            // requires LIBXML_XPATH_ENABLED
            'DOMXPath',
            'Dom\\XPath',
        ];

        self::$optionalmethods = [
            // requires LIBXML_SCHEMAS_ENABLED
            'Dom\\Document::schemaValidate',
            'Dom\\Document::schemaValidateSource',
            'Dom\\Document::relaxNgValidate',
            'Dom\\Document::relaxNgValidateSource',
            // aliases
            'Dom\\Document::getElementsByClassName',
            'Dom\\Element::getElementsByClassName',
            'Dom\\Element::insertAdjacentHTML',

            // requires ZEND_DEBUG
            'Dom\\HTMLDocument::debugGetTemplateCount',
        ];

        self::$ignoredclasses = [
            'dom\\domexception'
        ];

        parent::setUp();
    }
}
