<?php
/**
 * Unit tests for PHP_CompatInfo, ssh2 extension Reference
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 * @since      Class available since Release 3.0.0 of PHP_CompatInfo
 * @since      Class available since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */

namespace Bartlett\Tests\CompatInfoDb\Reference\Extension;

use Bartlett\Tests\CompatInfoDb\Reference\GenericTest;

/**
 * Tests for PHP_CompatInfo, retrieving components informations
 * about ssh2 extension
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://php5.laurent-laville.org/compatinfo/
 */
class Ssh2ExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass()
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
