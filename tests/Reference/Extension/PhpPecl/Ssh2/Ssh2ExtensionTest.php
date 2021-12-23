<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Ssh2;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, ssh2 extension Reference
 *
 * @since Release 3.0.0 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class Ssh2ExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalfunctions = array(
            // Requires PHP_SSH2_REMOTE_FORWARDING
            'ssh2_forward_accept',
            'ssh2_forward_listen',
            // Requires PHP_SSH2_POLL
            'ssh2_poll',
            // Requires libssh >= 1.2.3
            'ssh2_auth_agent',
        );
        self::$optionalconstants = array(
            // Requires PHP_SSH2_POLL
            'SSH2_POLLIN',
            'SSH2_POLLEXT',
            'SSH2_POLLOUT',
            'SSH2_POLLERR',
            'SSH2_POLLHUP',
            'SSH2_POLLNVAL',
            'SSH2_POLL_SESSION_CLOSED',
            'SSH2_POLL_CHANNEL_CLOSED',
            'SSH2_POLL_LISTENER_CLOSED',
        );

        parent::setUpBeforeClass();
    }
}
