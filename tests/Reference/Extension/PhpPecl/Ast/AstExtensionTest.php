<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Ast;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, ast extension Reference
 *
 * @since Release 1.24.0
 * @author Laurent Laville
 */
class AstExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // strange behavior since Ast 1.1.2 on CI with 'shivammathur/setup-php@v2' (SHA:c541c155eee45413f5b09a52248675b1a2575231)
            "ast\\flags\\DIM_ALTERNATIVE_SYNTAX",
        ];

        parent::setUpBeforeClass();
    }
}
