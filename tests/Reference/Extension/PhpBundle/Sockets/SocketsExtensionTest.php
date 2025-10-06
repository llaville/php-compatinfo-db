<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Sockets;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

use Exception;

/**
 * Unit tests for PHP_CompatInfo_Db, sockets extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class SocketsExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        if (PATH_SEPARATOR == ':') {
            self::$optionalconstants = array(
                // Win32 only (from ext/sockets/win32_socket_constants.h)
                'SOCKET_EDISCON',
                'SOCKET_EPROCLIM',
                'SOCKET_ESTALE',
                'SOCKET_HOST_NOT_FOUND',
                'SOCKET_NOTINITIALISED',
                'SOCKET_NO_ADDRESS',
                'SOCKET_NO_DATA',
                'SOCKET_NO_RECOVERY',
                'SOCKET_SYSNOTREADY',
                'SOCKET_TRY_AGAIN',
                'SOCKET_VERNOTSUPPORTED',
            );
        } else {
            self::$optionalconstants = array(
                // Unix only (from ext/sockets/unix_socket_constants.h)
                'SOCKET_E2BIG',
                'SOCKET_EADV',
                'SOCKET_EAGAIN',
                'SOCKET_EBADE',
                'SOCKET_EBADFD',
                'SOCKET_EBADMSG',
                'SOCKET_EBADR',
                'SOCKET_EBADRQC',
                'SOCKET_EBADSLT',
                'SOCKET_EBUSY',
                'SOCKET_ECHRNG',
                'SOCKET_ECOMM',
                'SOCKET_EEXIST',
                'SOCKET_EIDRM',
                'SOCKET_EIO',
                'SOCKET_EISDIR',
                'SOCKET_EISNAM',
                'SOCKET_EL2HLT',
                'SOCKET_EL2NSYNC',
                'SOCKET_EL3HLT',
                'SOCKET_EL3RST',
                'SOCKET_ELNRNG',
                'SOCKET_EMEDIUMTYPE',
                'SOCKET_EMLINK',
                'SOCKET_EMULTIHOP',
                'SOCKET_ENFILE',
                'SOCKET_ENOANO',
                'SOCKET_ENOCSI',
                'SOCKET_ENODATA',
                'SOCKET_ENODEV',
                'SOCKET_ENOENT',
                'SOCKET_ENOLCK',
                'SOCKET_ENOLINK',
                'SOCKET_ENOMEDIUM',
                'SOCKET_ENOMEM',
                'SOCKET_ENOMSG',
                'SOCKET_ENONET',
                'SOCKET_ENOSPC',
                'SOCKET_ENOSR',
                'SOCKET_ENOSTR',
                'SOCKET_ENOSYS',
                'SOCKET_ENOTBLK',
                'SOCKET_ENOTDIR',
                'SOCKET_ENOTTY',
                'SOCKET_ENOTUNIQ',
                'SOCKET_ENXIO',
                'SOCKET_EPERM',
                'SOCKET_EPIPE',
                'SOCKET_EPROTO',
                'SOCKET_EREMCHG',
                'SOCKET_EREMOTEIO',
                'SOCKET_ERESTART',
                'SOCKET_EROFS',
                'SOCKET_ESPIPE',
                'SOCKET_ESRMNT',
                'SOCKET_ESTRPIPE',
                'SOCKET_ETIME',
                'SOCKET_EUNATCH',
                'SOCKET_EXDEV',
                'SOCKET_EXFULL',
                // if def (from ext/sockets/sockets_arginfo.h)
                'TCP_QUICKACK',
                'TCP_REPAIR',
                'SO_BUSY_POLL',
            );
        }
        // Common to Windows and Unix
        // (from ext/sockets/ win32_socket_constants.h and unix_socket_constants.h)
        $tmp = array(
            'SOCKET_EACCES',
            'SOCKET_EADDRINUSE',
            'SOCKET_EADDRNOTAVAIL',
            'SOCKET_EAFNOSUPPORT',
            'SOCKET_EALREADY',
            'SOCKET_EBADF',
            'SOCKET_ECONNABORTED',
            'SOCKET_ECONNREFUSED',
            'SOCKET_ECONNRESET',
            'SOCKET_EDESTADDRREQ',
            'SOCKET_EDQUOT',
            'SOCKET_EFAULT',
            'SOCKET_EHOSTDOWN',
            'SOCKET_EHOSTUNREACH',
            'SOCKET_EINPROGRESS',
            'SOCKET_EINTR',
            'SOCKET_EINVAL',
            'SOCKET_EISCONN',
            'SOCKET_ELOOP',
            'SOCKET_EMFILE',
            'SOCKET_EMSGSIZE',
            'SOCKET_ENAMETOOLONG',
            'SOCKET_ENETDOWN',
            'SOCKET_ENETRESET',
            'SOCKET_ENETUNREACH',
            'SOCKET_ENOBUFS',
            'SOCKET_ENOPROTOOPT',
            'SOCKET_ENOTCONN',
            'SOCKET_ENOTEMPTY',
            'SOCKET_ENOTSOCK',
            'SOCKET_EOPNOTSUPP',
            'SOCKET_EPFNOSUPPORT',
            'SOCKET_EPROTONOSUPPORT',
            'SOCKET_EPROTOTYPE',
            'SOCKET_EREMOTE',
            'SOCKET_ESHUTDOWN',
            'SOCKET_ESOCKTNOSUPPORT',
            'SOCKET_ETIMEDOUT',
            'SOCKET_ETOOMANYREFS',
            'SOCKET_EUSERS',
            'SOCKET_EWOULDBLOCK',

            // from ext/sockets/sendrecvmsg.c
            // requires HAVE_IPV6
            'IPV6_RECVPKTINFO',
            'IPV6_PKTINFO',
            'IPV6_RECVHOPLIMIT',
            'IPV6_HOPLIMIT',
            'IPV6_RECVTCLASS',
            'IPV6_TCLASS',
            // if def
            'SCM_CREDS',
            'LOCAL_CREDS',
            'SCM_CREDS2',
            'LOCAL_CREDS_PERSISTENT',
            'SCM_CREDENTIALS',
            'SCM_RIGHTS',
            'SO_PASSCRED',

            // from ext/sockets/sockets.c
            // requires HAVE_IPV6
            'AF_INET6',
            // if def
            'SOCK_RDM',
            'MSG_EOR',
            'MSG_EOF',
            'MSG_CONFIRM',
            'MSG_ERRQUEUE',
            'MSG_NOSIGNAL',
            'MSG_DONTWAIT',
            'MSG_MORE',
            'MSG_WAITFORONE',
            'MSG_CMSG_CLOEXEC',
            'MSG_ZEROCOPY',
            'SO_BINDTODEVICE',
            'SO_REUSEPORT',
            'SO_FAMILY',
            'SO_LABEL',
            'SO_PEERLABEL',
            'SO_LISTENQLIMIT',
            'SO_LISTENQLEN',
            'SO_USER_COOKIE',
            'SO_SETFIB',
            'SO_ACCEPTFILTER',
            'SOL_FILTER',
            'FIL_ATTACH',
            'FIL_DETACH',
            'SO_DONTTRUNC',
            'SO_WANTMORE',
            'SO_MARK',
            'SO_RTABLE',
            'SO_INCOMING_CPU',
            'SO_MEMINFO',
            'SO_BPF_EXTENSIONS',
            'SKF_AD_OFF',
            'SKF_AD_PROTOCOL',
            'SKF_AD_PKTTYPE',
            'SKF_AD_IFINDEX',
            'SKF_AD_NLATTR',
            'SKF_AD_NLATTR_NEST',
            'SKF_AD_MARK',
            'SKF_AD_QUEUE',
            'SKF_AD_HATYPE',
            'SKF_AD_RXHASH',
            'SKF_AD_CPU',
            'SKF_AD_ALU_XOR_X',
            'SKF_AD_VLAN_TAG',
            'SKF_AD_VLAN_TAG_PRESENT',
            'SKF_AD_PAY_OFFSET',
            'SKF_AD_RANDOM',
            'SKF_AD_VLAN_TPID',
            'SKF_AD_MAX',
            'TCP_CONGESTION',
            'SO_ZEROCOPY',
            'TCP_NODELAY',
            'TCP_NOTSENT_LOWAT',
            'TCP_DEFER_ACCEPT',
            'TCP_KEEPALIVE',
            'TCP_KEEPIDLE',
            'TCP_KEEPINTVL',
            'TCP_KEEPCNT',
            // requires HAS_MCAST_EXT
            'MCAST_BLOCK_SOURCE',
            'MCAST_UNBLOCK_SOURCE',
            'MCAST_JOIN_SOURCE_GROUP',
            'MCAST_LEAVE_SOURCE_GROUP',
            // requires HAVE_IPV6
            'IPPROTO_IPV6',
            'IPV6_MULTICAST_IF',
            'IPV6_MULTICAST_HOPS',
            'IPV6_MULTICAST_LOOP',
            'IPV6_UNICAST_HOPS',
            // if def
            'IPV6_V6ONLY',
            // requires HAVE_AI_V4MAPPED
            'AI_V4MAPPED',
            // requires HAVE_AI_ALL
            'AI_ALL',
            // requires HAVE_AI_IDN
            'AI_IDN',
            'AI_CANONIDN',
            //
            'AI_IDN_ALLOW_UNASSIGNED',
            'AI_IDN_USE_STD3_ASCII_RULES',
            // if def
            'AI_NUMERICSERV',
            'SOL_LOCAL',
            'TCP_DEFER_ACCEPT',
            // if def
            'IP_BIND_ADDRESS_NO_PORT',
            // if def IPPROTO_UDPLITE
            'SOL_UDPLITE',
            // if def
            'UDPLITE_SEND_CSCOV',
            'UDPLITE_RECV_CSCOV',
            // if def
            'SO_ATTACH_REUSEPORT_CBPF',
            'SO_DETACH_FILTER',
            'SO_DETACH_BPF',

            // if def (from ext/sockets/sockets_arginfo.h)
            'AF_DIVERT',
            'IP_DONTFRAG',
            'IP_MTU_DISCOVER',
            'IP_PMTUDISC_DO',
            'IP_PMTUDISC_DONT',
            'IP_PMTUDISC_WANT',
            'IP_PMTUDISC_PROBE',
            'IP_PMTUDISC_INTERFACE',
            'IP_PMTUDISC_OMIT',
            'SO_EXCLBIND',
            'SO_LINGER_SEC',
            'SOCK_CONN_DGRAM',
            'SOCK_DCCP',
            'SOCK_CLOEXEC',
            'SOCK_NONBLOCK',
            'TCP_SYNCNT',
            'IPPROTO_ICMP',
            'IPPROTO_ICMPV6',

            // if def(AF_PACKET)
            'AF_PACKET',

            // if definded(ETH_P_ALL)
            'ETH_P_IP',
            'ETH_P_IPV6',
            'ETH_P_LOOP',
            'ETH_P_ALL',
        );
        self::$optionalconstants = array_merge(self::$optionalconstants, $tmp);

        self::$optionalfunctions = array(
            // requires HAVE_SOCKETPAIR
            'socket_create_pair',
            // requires HAVE_SOCKATMARK
            'socket_atmark',
        );

        parent::setUpBeforeClass();
    }
}
