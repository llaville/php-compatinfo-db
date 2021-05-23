<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, rdkafka extension Reference
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpPecl\Rdkafka;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.5.0 of PHP_CompatInfo_Db
 */
class RdkafkaExtensionTest extends GenericTest
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
