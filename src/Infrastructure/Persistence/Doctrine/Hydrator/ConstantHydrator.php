<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Constant_ as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Constant_ as Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @since Release 3.0.0
 */
final class ConstantHydrator implements HydratorInterface
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
            'name' => $object->getName(),
            'declaring_class' => $object->getDeclaringClass(),
            'extension' => $object->getExtension()->getName(),
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

        $object->setName($data['name']);
        $object->setDeclaringClass($data['class_name'] ?? null);
        $object->setExtMin($data['ext_min']);
        $object->setExtMax($data['ext_max'] ?? null);
        $object->setPhpMin($data['php_min']);
        $object->setPhpMax($data['php_max'] ?? null);

        $dependencies = new ArrayCollection(
            (new DependencyHydrator())->hydrateArrays($data['dependencies'] ?? [])
        );
        $object->addDependencies($dependencies);

        return $object;
    }

    /**
     * @param Entity $entity
     * @return Domain
     */
    public function toDomain(Entity $entity): Domain
    {
        $hydrator = new DependencyHydrator();
        $dependencies = [];
        foreach ($entity->getDependencies() as $dependencyEntity) {
            $dependencies[] = $hydrator->toDomain($dependencyEntity);
        }

        return new Domain(
            $entity->getName(),
            $entity->getDeclaringClass(),
            $entity->getExtension()->getName(),
            $entity->getExtMin(),
            $entity->getExtMax(),
            $entity->getPhpMin(),
            $entity->getPhpMax(),
            $dependencies
        );
    }
}
