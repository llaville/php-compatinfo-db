<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Extension as Domain;
use Bartlett\CompatInfoDb\Domain\ValueObject\Release;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension as Entity;

use function array_map;
use function version_compare;
use const PHP_VERSION;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
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
            'deprecated' => $object->isDeprecated(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class();

        if (!$object instanceof Entity) {
            return $object;
        }

        $object->setName($data['name']);
        $object->setType($data['type']);
        $object->setDeprecated($data['deprecated'] ?? false);

        if (count($data['releases'])) {
            $releases = (new ReleaseHydrator())->hydrateArrays($data['releases']);
            $object->addReleases($releases);
            // latest release declared in references
            /** @var Release $latest */
            $latest = end($releases);
            $object->setVersion($latest->getVersion());
        }

        $iniEntries = (new IniEntryHydrator())->hydrateArrays($data['iniEntries']);
        $object->addIniEntries($iniEntries);

        foreach (['constants', 'const'] as $component) {
            $constants = (new ConstantHydrator())->hydrateArrays($data[$component]);
            $object->addConstants($constants);
        }

        foreach (['functions', 'methods'] as $component) {
            $functions = (new FunctionHydrator())->hydrateArrays($data[$component]);
            $object->addFunctions($functions);
        }

        foreach (['classes', 'interfaces'] as $component) {
            $data[$component] = array_map(function ($item) use ($component) {
                $item['is_interface'] = ('interfaces' === $component);
                return $item;
            }, $data[$component]);

            $classes = (new ClassHydrator())->hydrateArrays($data[$component]);
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
            if ($this->shouldSkip($iniEntity)) {
                continue;
            }
            $iniEntries[$iniEntity->getName()] = $hydrator->toDomain($iniEntity);
        }

        $constants = [];
        $hydrator = new ConstantHydrator();
        foreach ($entity->getConstants() as $constantEntity) {
            if ($this->shouldSkip($constantEntity)) {
                continue;
            }
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
            if ($this->shouldSkip($functionEntity)) {
                continue;
            }
            $declaringClass = $functionEntity->getDeclaringClass();
            if (empty($declaringClass)) {
                // function
                $functions[$functionEntity->getName()] = $hydrator->toDomain($functionEntity);
            } else {
                // class method
                $functions[$declaringClass . '::' . $functionEntity->getName()] = $hydrator->toDomain($functionEntity);
            }
        }

        $classes = [];
        $hydrator = new ClassHydrator();
        foreach ($entity->getClasses() as $classEntity) {
            if ($this->shouldSkip($classEntity)) {
                continue;
            }
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
            $entity->isDeprecated(),
            $iniEntries,
            $constants,
            $functions,
            $classes,
            $dependencies,
            $releases
        );
    }

    /**
     * When there are multiple copy of same element, return only item that is supported by current platform.
     *
     * See Xmlrpc extension example that was bundled before PHP 8.0, and now is an external pecl extension.
     * @see https://github.com/llaville/php-compatinfo-db/issues/64
     *
     *
     * @param object $object
     * @return bool
     */
    private function shouldSkip(object $object): bool
    {
        return version_compare($object->getPhpMin(), PHP_VERSION, 'gt');
    }
}
