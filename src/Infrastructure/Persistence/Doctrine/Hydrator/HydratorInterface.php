<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
interface HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array<string, string|bool|mixed> $data
     */
    public function hydrate(array $data, string $class): object;

    /**
     * Hydrate array of $object with the provided $data.
     *
     * @param array<int|string, mixed> $data
     * @return array<object>
     */
    public function hydrateArrays(array $data): array;

    /**
     * Extract values from an object.
     *
     * @return array<string, DateTimeImmutable|string|bool|null>
     */
    public function extract(object $object): array;
}
