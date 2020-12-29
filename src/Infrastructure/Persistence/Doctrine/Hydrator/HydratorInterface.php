<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

/**
 * @since Release 3.0.0
 */
interface HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param string $class
     * @return object
     */
    public function hydrate(array $data, string $class): object;

    /**
     * Hydrate array of $object with the provided $data.
     *
     * @param array[] $data
     * @return array
     */
    public function hydrateArrays(array $data): array;

    /**
     * Extract values from an object.
     *
     * @param object $object
     * @return array
     */
    public function extract(object $object) : array;
}
