<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Tests\Reference\Extension\PhpBundle\Spl;

use Bartlett\CompatInfoDb\Tests\Reference\GenericTest;

/**
 * Unit tests for PHP_CompatInfo_Db, spl extension Reference
 *
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 * @author Laurent Laville
 * @author Remi Collet
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
