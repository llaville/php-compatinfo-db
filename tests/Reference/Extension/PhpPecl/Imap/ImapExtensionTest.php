<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Imap;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, imap extension Reference
 *
 * @since Release 6.12.0 of PHP_CompatInfo_Db, now imap was moved to PECL
 * @author Laurent Laville
 * @author Remi Collet
 */
class ImapExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            'LATT_REFERRAL',        // ifdef LATT_REFERRAL
            'LATT_HASCHILDREN',     // ifdef LATT_HASCHILDREN
            'LATT_HASNOCHILDREN',   // ifdef LATT_HASNOCHILDREN
        ];

        self::$optionalfunctions = [
            // ifdef HAVE_IMAP_MUTF7
            'imap_mutf7_to_utf8',
            'imap_utf8_to_mutf7',
            // if (defined(HAVE_IMAP2000) || defined(HAVE_IMAP2001))
            'imap_get_quota',
            'imap_get_quotaroot',
            'imap_set_quota',
            'imap_setacl',
            'imap_getacl',
        ];

        parent::setUpBeforeClass();
    }
}
