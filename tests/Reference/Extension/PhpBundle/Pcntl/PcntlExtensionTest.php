<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Pcntl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, pcntl extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class PcntlExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalconstants = [
            // requires HAVE_WAITID && HAVE_POSIX_IDTYPES
            'P_ALL',
            'P_PID',
            'P_PGID',
            // requires HAVE_WAITID && HAVE_LINUX_IDTYPES
            'P_PIDFD',
            // requires HAVE_WAITID && HAVE_NETBSD_IDTYPES
            'P_UID',
            'P_GID',
            'P_SID',
            // requires HAVE_WAITID && HAVE_FREEBSD_IDTYPES
            'P_JAILID',
            //
            'SI_NOINFO',
            // requires HAVE_WCONTINUED
            'WCONTINUED',
            // requires defined (HAVE_DECL_WEXITED) && HAVE_DECL_WEXITED == 1
            'WEXITED',
            // requires defined (HAVE_DECL_WSTOPPED) && HAVE_DECL_WSTOPPED == 1
            'WSTOPPED',
            // requires defined (HAVE_DECL_WNOWAIT) && HAVE_DECL_WNOWAIT == 1
            'WNOWAIT',
        ];
        self::$optionalfunctions = [
            // requires HAVE_WCONTINUED
            'pcntl_wifcontinued',
            // requires HAVE_PTHREAD_SET_QOS_CLASS_SELF_NP
            'pcntl_getqos_class',
            'pcntl_setqos_class',
            // requires HAVE_SCHED_GETCPU
            'pcntl_getcpu',
            // requires HAVE_SCHED_SETAFFINITY
            'pcntl_getcpuaffinity',
            'pcntl_setcpuaffinity',
            // requires HAVE_PIDFD_OPEN
            'pcntl_setns',
            // requires defined (HAVE_WAITID)
            //       && defined (HAVE_POSIX_IDTYPES)
            //       && defined (HAVE_DECL_WEXITED)
            //       && HAVE_DECL_WEXITED == 1
            'pcntl_waitid',
        ];

        parent::setUpBeforeClass();
    }
}
