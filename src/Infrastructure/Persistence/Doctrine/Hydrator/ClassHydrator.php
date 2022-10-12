<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Class_ as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Class_ as Entity;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class ClassHydrator implements HydratorInterface
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
            'is_interface' => $object->isInterface(),
            'extension' => $object->getExtension()->getName(),
            'ext_min' => $object->getExtMin(),
            'ext_max' => $object->getExtMax(),
            'php_min' => $object->getPhpMin(),
            'php_max' => $object->getPhpMax(),
            'is_abstract' => (bool) ($object->getFlags() & Class_::MODIFIER_ABSTRACT),
            'is_final' => (bool) ($object->getFlags() & Class_::MODIFIER_FINAL),
            'polyfill' => $object->getPolyfill(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class();

        if (!$object instanceof Entity) {
            $object = new Entity();
        }

        $object->setName($data['name']);
        $object->setInterface($data['is_interface']);
        $object->setExtMin($data['ext_min']);
        $object->setExtMax($data['ext_max'] ?? null);
        $object->setPhpMin($data['php_min']);
        $object->setPhpMax($data['php_max'] ?? null);
        $object->setFlags(Class_::MODIFIER_PUBLIC);
        $object->setPolyfill($data['polyfill'] ?? null);

        $dependencies = (new DependencyHydrator())->hydrateArrays($data['dependencies'] ?? []);
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
            $entity->isInterface(),
            $entity->getExtension()->getName(),
            $entity->getExtMin(),
            $entity->getExtMax(),
            $entity->getPhpMin(),
            $entity->getPhpMax(),
            $dependencies,
            $entity->getFlags(),
            $entity->getPolyfill()
        );
    }
}
