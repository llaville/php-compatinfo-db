<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Rdkafka;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTestCase;

/**
 * Unit tests for PHP_CompatInfo_Db, rdkafka extension Reference
 *
 * @since Release 3.5.0 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
 */
class RdkafkaExtensionTest extends GenericTestCase
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // depends on HAS_RD_KAFKA_PARTITIONER_MURMUR2
        self::$optionalconstants = [
            'RD_KAFKA_MSG_PARTITIONER_MURMUR2',
            'RD_KAFKA_MSG_PARTITIONER_MURMUR2_RANDOM',
        ];

        // depends on HAVE_RD_KAFKA_MESSAGE_HEADERS
        self::$optionalmethods = [
            'RdKafka\\ProducerTopic::producev',
        ];

        parent::setUpBeforeClass();
    }
}
