<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Release as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Release as Entity;

use DateTimeImmutable;

/**
 * @since Release 3.0.0
 */
final class ReleaseHydrator implements HydratorInterface
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

        return [
            'version' => $object->getVersion(),
            'date' => $object->getDate(),
            'state' => $object->getState(),
            'ext_min' => $object->getExtMin(),
            'ext_max' => $object->getExtMax(),
            'php_min' => $object->getPhpMin(),
            'php_max' => $object->getPhpMax(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class;

        if (!$object instanceof Entity) {
            $object = new Entity();
        }

        $object->setVersion($data['rel_version']);
        $object->setDate(new DateTimeImmutable($data['rel_date']));
        $object->setState($data['rel_state']);
        $object->setExtMin('');
        $object->setExtMax($data['ext_max'] ?? null);
        $object->setPhpMin($data['php_min']);
        $object->setPhpMax($data['php_max'] ?? null);

        return $object;
    }

    /**
     * @param Entity $entity
     * @return Domain
     */
    public function toDomain(Entity $entity): Domain
    {
        return new Domain(
            $entity->getVersion(),
            $entity->getDate(),
            $entity->getState(),
            $entity->getExtMin(),
            $entity->getExtMax(),
            $entity->getPhpMin(),
            $entity->getPhpMax()
        );
    }
}
