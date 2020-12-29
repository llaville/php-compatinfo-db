<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

/**
 * @since Release 3.0.0
 */
trait HydrationArraysTrait
{
    /**
     * {@inheritDoc}
     */
    public function hydrateArrays(array $data): array
    {
        $valueObjects = [];

        foreach ($data as $values) {
            $valueObjects[] = $this->hydrate($values);
        }

        return $valueObjects;
    }
}
