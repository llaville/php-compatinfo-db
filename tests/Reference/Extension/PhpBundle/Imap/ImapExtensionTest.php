<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Imap;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use function extension_loaded;
use function phpversion;
use function version_compare;

/**
 * Unit tests for PHP_CompatInfo_Db, imap extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
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

        if (extension_loaded('imap')) {
            if (version_compare(phpversion('imap'), '4.0.0', 'ge')) {
                // components only available since PECL version 1.0
                self::$optionalfunctions[] = 'imap_is_open';

                self::$optionalclasses = [
                    'IMAP\\Connection',
                ];
            }
        }

        parent::setUpBeforeClass();
    }
}
