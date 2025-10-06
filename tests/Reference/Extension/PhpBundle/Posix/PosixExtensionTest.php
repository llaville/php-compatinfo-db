<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Posix;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use Exception;

/**
 * Unit tests for PHP_CompatInfo_Db, posix extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class PosixExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // Requires _SC_ARG_MAX
            'POSIX_SC_ARG_MAX',
            // Requires _SC_PAGESIZE
            'POSIX_SC_PAGESIZE',
            // Requires _SC_NPROCESSORS_CONF
            'POSIX_SC_NPROCESSORS_CONF',
            // Requires _SC_NPROCESSORS_ONLN
            'POSIX_SC_NPROCESSORS_ONLN',
            // Requires _SC_OPEN_MAX
            'POSIX_SC_OPEN_MAX',
            // Requires _PC_LINK_MAX
            'POSIX_PC_LINK_MAX',
            // Requires _PC_MAX_CANON
            'POSIX_PC_MAX_CANON',
            // Requires _PC_MAX_INPUT
            'POSIX_PC_MAX_INPUT',
            // Requires _PC_NAME_MAX
            'POSIX_PC_NAME_MAX',
            // Requires _PC_PATH_MAX
            'POSIX_PC_PATH_MAX',
            // Requires _PC_PIPE_BUF
            'POSIX_PC_PIPE_BUF',
            // Requires _PC_CHOWN_RESTRICTED
            'POSIX_PC_CHOWN_RESTRICTED',
            // Requires _PC_NO_TRUNC
            'POSIX_PC_NO_TRUNC',
            // Requires _PC_ALLOC_SIZE_MIN
            'POSIX_PC_ALLOC_SIZE_MIN',
            // Requires _PC_SYMLINK_MAX
            'POSIX_PC_SYMLINK_MAX',
        ];

        self::$optionalfunctions = [
            // Requires HAVE_EACCESS
            'posix_eaccess',
            // Requires HAVE_POSIX_PATHCONF
            'posix_pathconf',
            // Requires HAVE_FPATHCONF
            'posix_pathconf',
            'posix_fpathconf',
        ];

        parent::setUpBeforeClass();
    }
}
