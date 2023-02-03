<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Amqp;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, amqp extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class AmqpExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (PATH_SEPARATOR == ';') {
            // Win*
            // only available since version 1.0.8
            array_push(self::$ignoredconstants, 'AMQP_OS_SOCKET_TIMEOUT_ERRNO');
            // only available since version 1.0.0
            array_push(self::$ignoredclasses, 'AMQPChannel', 'AMQPChannelException', 'AMQPEnvelope');
        } else {
            // *nix
        }

        // related to prototype methods issue
        // @see https://github.com/php-amqp/php-amqp/issues/398
        self::$ignoredmethods = [
            'AMQPEnvelope::getContentType',
            'AMQPEnvelope::getContentEncoding',
            'AMQPEnvelope::getHeaders',
            'AMQPEnvelope::getDeliveryMode',
            'AMQPEnvelope::getPriority',
            'AMQPEnvelope::getCorrelationId',
            'AMQPEnvelope::getReplyTo',
            'AMQPEnvelope::getExpiration',
            'AMQPEnvelope::getMessageId',
            'AMQPEnvelope::getTimestamp',
            'AMQPEnvelope::getType',
            'AMQPEnvelope::getUserId',
            'AMQPEnvelope::getAppId',
            'AMQPEnvelope::getClusterId',
        ];

        parent::setUpBeforeClass();
    }
}
