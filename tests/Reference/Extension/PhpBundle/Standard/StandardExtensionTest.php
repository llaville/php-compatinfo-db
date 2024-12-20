<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Standard;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, standard extension Reference.
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class StandardExtensionTest extends GenericTestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$ignoredfunctions = array(
            // functions moved from internal to ereg extension in 5.3.0
            'ereg_replace',
            'ereg',
            'eregi_replace',
            'eregi',
            'split',
            'spliti',
            'sql_regcase',
        );

        if (PATH_SEPARATOR == ':') {
            self::$optionalfunctions = array(
                // requires HAVE_CHROOT
                'chroot',
                // remove in some Linux distribution (Redhat, ...)
                'php_egg_logo_guid',
                // not detected because of https://bugs.php.net/66218
                'cli_get_process_title',
                'cli_set_process_title',
                // alias
                'getdir',
                // windows only
                'sapi_windows_vt100_support',
            );
        } else {
            self::$optionalfunctions = array(
                // requires HAVE_NL_LANGINFO (linux only)
                'nl_langinfo',
                // requires HAVE_STRPTIME (linux only)
                'strptime',
                // requires HAVE_STRFMON (linux only)
                'money_format',
                // requires HAVE_GETRUSAGE (linux only)
                'getrusage',
                // requires HAVE_CHROOT
                'chroot',
                // requires HAVE_FTOK (linux only)
                'ftok',
                // requires HAVE_NICE (linux only)
                'proc_nice',
                // requires HAVE_GETLOADAVG (linux only)
                'sys_getloadavg',
                // Linux only
                'lchgrp',
                'lchown',
                // native support in 5.3 only (windows)
                'acosh',
                'asinh',
                'atanh',
                'dns_check_record',
                'dns_get_mx',
                'dns_get_record',
                'expm1',
                'log1p',
                'checkdnsrr',
                'fnmatch',
                'getmxrr',
                'getopt',
                'inet_ntop',
                'inet_pton',
                'link',
                'linkinfo',
                'readlink',
                'stream_socket_pair',
                'symlink',
                'time_nanosleep',
                'time_sleep_until',
            );

            if (php_sapi_name() != 'cli') {
                // dl function still exists in CLI but was removed from other SAPI since PHP 5.3
                array_push(self::$optionalfunctions, 'dl');
            }
        }
            self::$optionalconstants = array(
                // requires syslog
                'LOG_LOCAL0',
                'LOG_LOCAL1',
                'LOG_LOCAL2',
                'LOG_LOCAL3',
                'LOG_LOCAL4',
                'LOG_LOCAL5',
                'LOG_LOCAL6',
                'LOG_LOCAL7',
                // requires HAVE_FNMATCH (linux only)
                'FNM_NOESCAPE',
                'FNM_PATHNAME',
                'FNM_PERIOD',
                'FNM_CASEFOLD',
                // requires HAVE_LIBINTL
                'LC_MESSAGES',
                // requires HAVE_NL_LANGINFO
                'ABDAY_1',
                'ABDAY_2',
                'ABDAY_3',
                'ABDAY_4',
                'ABDAY_5',
                'ABDAY_6',
                'ABDAY_7',
                'DAY_1',
                'DAY_2',
                'DAY_3',
                'DAY_4',
                'DAY_5',
                'DAY_6',
                'DAY_7',
                'ABMON_1',
                'ABMON_2',
                'ABMON_3',
                'ABMON_4',
                'ABMON_5',
                'ABMON_6',
                'ABMON_7',
                'ABMON_8',
                'ABMON_9',
                'ABMON_10',
                'ABMON_11',
                'ABMON_12',
                'MON_1',
                'MON_2',
                'MON_3',
                'MON_4',
                'MON_5',
                'MON_6',
                'MON_7',
                'MON_8',
                'MON_9',
                'MON_10',
                'MON_11',
                'MON_12',
                'AM_STR',
                'PM_STR',
                'D_T_FMT',
                'D_FMT',
                'T_FMT',
                'T_FMT_AMPM',
                'ERA',
                'ERA_YEAR',
                'ERA_D_T_FMT',
                'ERA_D_FMT',
                'ERA_T_FMT',
                'ALT_DIGITS',
                'INT_CURR_SYMBOL',
                'MON_DECIMAL_POINT',
                'MON_THOUSANDS_SEP',
                'MON_GROUPING',
                'POSITIVE_SIGN',
                'NEGATIVE_SIGN',
                'INT_FRAC_DIGITS',
                'FRAC_DIGITS',
                'P_CS_PRECEDES',
                'P_SEP_BY_SPACE',
                'N_CS_PRECEDES',
                'N_SEP_BY_SPACE',
                'P_SIGN_POSN',
                'N_SIGN_POSN',
                // requires DECIMAL_POINT
                'DECIMAL_POINT',
                // requires CURRENCY_SYMBOL
                'CURRENCY_SYMBOL',
                //
                'CRNCYSTR',
                // requires RADIXCHAR
                'RADIXCHAR',
                // requires THOUSANDS_SEP
                'THOUSANDS_SEP',
                // requires THOUSEP
                'THOUSEP',
                //
                'GROUPING',
                'YESEXPR',
                'NOEXPR',
                'YESSTR',
                'NOSTR',
                // requires CODESET
                'CODESET',
                // native support in 5.3 only (windows)
                'DNS_A',
                'DNS_NS',
                'DNS_CNAME',
                'DNS_SOA',
                'DNS_PTR',
                'DNS_HINFO',
                'DNS_CAA',
                'DNS_MX',
                'DNS_TXT',
                'DNS_SRV',
                'DNS_NAPTR',
                'DNS_AAAA',
                'DNS_A6',
                'DNS_ANY',
                'DNS_ALL',
                // Stream not supported
                'STREAM_IPPROTO_TCP',
                'STREAM_IPPROTO_UDP',
                'STREAM_IPPROTO_ICMP',
                'STREAM_IPPROTO_RAW',
            );

        // requires HAVE_ARGON2LIB
        array_push(
            self::$optionalconstants,
            'PASSWORD_ARGON2I',
            'PASSWORD_ARGON2ID',
            'PASSWORD_ARGON2_DEFAULT_MEMORY_COST',
            'PASSWORD_ARGON2_DEFAULT_TIME_COST',
            'PASSWORD_ARGON2_DEFAULT_THREADS',
            'PASSWORD_ARGON2_PROVIDER'
        );

        // WARNING: strange to find it on PHP 7.2.x versions, while it supposed to appears since PHP 7.3.0alpha
        // probablt a GenericTest issue to fix (see also MbstringExtensionTest)
        self::$ignoredfunctions = [
            'array_key_first',
            'array_key_last',
            'hrtime',
            'is_countable',
            // requires ZEND_DEBUG
            'config_get_hash',
        ];

        parent::setUpBeforeClass();
    }
}
