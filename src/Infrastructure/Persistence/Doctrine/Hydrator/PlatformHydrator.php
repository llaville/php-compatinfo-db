<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform as Entity;

/**
 * @since Release 3.0.0
 */
final class PlatformHydrator implements HydratorInterface
{
    use HydrationArraysTrait;

    /**
     * {@inheritDoc}
     */
    public function extract(object $object): array
    {
        if (!$object instanceof Entity) {
            return [];
        }
/*
        $hydrator = new ExtensionHydrator();

        $mappedCollection = $object->getExtensions()->map(function($extension) use ($hydrator) {
            return $hydrator->extract($extension);
        });
*/
        return [
            'description' => $object->getDescription(),
            'version' => $object->getVersion(),
            'created_at' => $object->getCreatedAt(),
            //'extensions' => [], //$mappedCollection->toArray(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class;

        if (!$object instanceof Entity) {
            return $object;
        }

        return $object;
    }

    /**
     * @param Entity $entity
     * @return Domain
     */
    public function toDomain(Entity $entity): Domain
    {
        return new Domain(
            $entity->getDescription(),
            $entity->getVersion(),
            $entity->getCreatedAt(),
            $entity->getExtensions()
        );
    }
}
