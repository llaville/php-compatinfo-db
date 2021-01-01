<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Extension as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension as Entity;

use Doctrine\Common\Collections\ArrayCollection;

use function array_map;

/**
 * @since Release 3.0.0
 */
final class ExtensionHydrator implements HydratorInterface
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
            'description' => $object->getDescription(),
            'name' => $object->getName(),
            'version' => $object->getVersion(),
            'type' => $object->getType(),
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

        $object->setName($data['name']);
        $object->setType($data['type']);

        $releases = new ArrayCollection(
            (new ReleaseHydrator())->hydrateArrays($data['releases'])
        );
        $object->addReleases($releases);
        // latest release declared in references
        $object->setVersion($releases->last()->getVersion());

        $iniEntries = new ArrayCollection(
            (new IniEntryHydrator())->hydrateArrays($data['iniEntries'])
        );
        $object->addIniEntries($iniEntries);

        foreach (['constants', 'const'] as $component) {
            $constants = new ArrayCollection(
                (new ConstantHydrator())->hydrateArrays($data[$component])
            );
            $object->addConstants($constants);
        }

        foreach (['functions', 'methods'] as $component) {
            $functions = new ArrayCollection(
                (new FunctionHydrator())->hydrateArrays($data[$component])
            );
            $object->addFunctions($functions);
        }

        foreach (['classes', 'interfaces'] as $component) {
            $data[$component] = array_map(function ($item) use ($component) {
                $item['is_interface'] = ('interfaces' === $component);
                return $item;
            }, $data[$component]);

            $classes = new ArrayCollection(
                (new ClassHydrator())->hydrateArrays($data[$component])
            );
            $object->addClasses($classes);
        }

        return $object;
    }

    /**
     * @param Entity $entity
     * @return Domain
     */
    public function toDomain(Entity $entity): Domain
    {
        $iniEntries = [];
        $hydrator = new IniEntryHydrator();
        foreach ($entity->getIniEntries() as $iniEntity) {
            $iniEntries[$iniEntity->getName()] = $hydrator->toDomain($iniEntity);
        }

        $constants = [];
        $hydrator = new ConstantHydrator();
        foreach ($entity->getConstants() as $constantEntity) {
            $declaringClass = $constantEntity->getDeclaringClass();
            if (empty($declaringClass)) {
                $constants[$constantEntity->getName()] = $hydrator->toDomain($constantEntity);
            } else {
                $constants[$declaringClass . '::' . $constantEntity->getName()] = $hydrator->toDomain($constantEntity);
            }
        }

        $functions = [];
        $hydrator = new FunctionHydrator();
        foreach ($entity->getFunctions() as $functionEntity) {
            $declaringClass = $functionEntity->getDeclaringClass();
            if (empty($declaringClass)) {
                // function
                $functions[$functionEntity->getName()] = $hydrator->toDomain($functionEntity);
            } else {
                // class method
                $functions[$declaringClass . '::'. $functionEntity->getName()] = $hydrator->toDomain($functionEntity);
            }
        }

        $classes = [];
        $hydrator = new ClassHydrator();
        foreach ($entity->getClasses() as $classEntity) {
            $classes[$classEntity->getName()] = $hydrator->toDomain($classEntity);
        }

        $dependencies = [];
        $hydrator = new DependencyHydrator();
        foreach ($entity->getDependencies() as $dependencyEntity) {
            $dependencies[$dependencyEntity->getName()] = $hydrator->toDomain($dependencyEntity);
        }

        $releases = [];
        $hydrator = new ReleaseHydrator();
        foreach ($entity->getReleases() as $releaseEntity) {
            $releases[$releaseEntity->getVersion()] = $hydrator->toDomain($releaseEntity);
        }

        return new Domain(
            $entity->getName(),
            $entity->getVersion(),
            $entity->getType(),
            $iniEntries,
            $constants,
            $functions,
            $classes,
            $dependencies,
            $releases
        );
    }
}