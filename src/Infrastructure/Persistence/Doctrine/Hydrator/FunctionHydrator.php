<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Class_;
use Bartlett\CompatInfoDb\Domain\ValueObject\Function_ as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Function_ as Entity;

use function explode;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class FunctionHydrator implements HydratorInterface
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
            'parameters' => $object->getParameters(),
            'excludes' => $object->getExcludes(),
            'is_abstract' => (bool) ($object->getFlags() & Class_::MODIFIER_ABSTRACT),
            'is_final' => (bool) ($object->getFlags() & Class_::MODIFIER_FINAL),
            'is_public' => (bool) ($object->getFlags() & Class_::MODIFIER_PUBLIC),
            'is_protected' => (bool) ($object->getFlags() & Class_::MODIFIER_PROTECTED),
            'is_private' => (bool) ($object->getFlags() & Class_::MODIFIER_PRIVATE),
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
        $object->setDeclaringClass($data['class_name'] ?? null);
        $object->setExtMin($data['ext_min']);
        $object->setExtMax($data['ext_max'] ?? null);
        $object->setPhpMin($data['php_min']);
        $object->setPhpMax($data['php_max'] ?? null);
        $object->setParameters(isset($data['parameters']) ? explode(',', $data['parameters']) : null);
        $object->setExcludes(isset($data['php_excludes']) ? explode(',', $data['php_excludes']) : null);
        $object->setPrototype($data['prototype'] ?? null);

        $flags = Class_::MODIFIER_PUBLIC;
        if (isset($data['static']) && $data['static'] === true) {
            $flags = $flags | Class_::MODIFIER_STATIC;
        }
        if (isset($data['abstract']) && $data['abstract'] === true) {
            $flags = $flags | Class_::MODIFIER_ABSTRACT;
        }
        $object->setFlags($flags);

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
            $entity->getDeclaringClass(),
            $entity->getExtension()->getName(),
            $entity->getExtMin(),
            $entity->getExtMax(),
            $entity->getPhpMin(),
            $entity->getPhpMax(),
            $entity->getParameters(),
            $entity->getExcludes(),
            $dependencies,
            $entity->getFlags()
        );
    }
}
