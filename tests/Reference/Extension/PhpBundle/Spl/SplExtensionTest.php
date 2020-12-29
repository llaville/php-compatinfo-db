<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, spl extension Reference
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

namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Spl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
class SplExtensionTest extends GenericTest
{
    /**
     * Sets up the shared fixture.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$ignoredmethods = [
            'ArrayObject::__unserialize',
            'ArrayObject::__serialize',
            'ArrayObject::__debugInfo',
            'ArrayIterator::__unserialize',
            'ArrayIterator::__serialize',
            'ArrayIterator::__debugInfo',
            'SplFileInfo::__debugInfo',
            'SplDoublyLinkedList::__debugInfo',
            'SplDoublyLinkedList::__unserialize',
            'SplDoublyLinkedList::__serialize',
            'SplHeap::__debugInfo',
            'SplPriorityQueue::__debugInfo',
            'SplObjectStorage::__debugInfo',
            'SplObjectStorage::__unserialize',
            'SplObjectStorage::__serialize',
            'MultipleIterator::__debugInfo',
        ];

        parent::setUpBeforeClass();
    }
}
