<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\IniEntry as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\IniEntry as Entity;

use Deprecated;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class IniEntryHydrator implements HydratorInterface
{
    use HydrationArraysTrait;
    use DeprecationHydratorTrait;

    /**
     * @return array<string, array<string, string|null>|string|null>
     */
    public function extract(object $object): array
    {
        if (!$object instanceof Entity) {
            return [];
        }

        return [
            'name' => $object->getName(),
            'ext_min' => $object->getExtMin(),
            'ext_max' => $object->getExtMax(),
            'php_min' => $object->getPhpMin(),
            'php_max' => $object->getPhpMax(),
            'deprecated' => $object->getDeprecated(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class();

        if (!$object instanceof Entity) {
            $object = new Entity();
        }

        $object->setName($data['name']);
        $object->setExtMin($data['ext_min']);
        $object->setExtMax($data['ext_max'] ?? null);
        $object->setPhpMin($data['php_min']);
        $object->setPhpMax($data['php_max'] ?? null);

        if (isset($data['deprecated'])) {
            $this->hydrateDeprecation($data['deprecated'], $object);
        }

        $dependencies = (new DependencyHydrator())->hydrateArrays($data['dependencies'] ?? []);
        $object->addDependencies($dependencies);

        return $object;
    }

    public function toDomain(Entity $entity): Domain
    {
        $hydrator = new DependencyHydrator();
        $dependencies = [];
        foreach ($entity->getDependencies() as $dependencyEntity) {
            $dependencies[] = $hydrator->toDomain($dependencyEntity);
        }

        $deprecation = $entity->getDeprecated();
        if (is_array($deprecation)) {
            $deprecated = new Deprecated($deprecation['message'], $deprecation['since']);
        } else {
            $deprecated = null;
        }

        return new Domain(
            $entity->getName(),
            $entity->getExtMin(),
            $entity->getExtMax(),
            $entity->getPhpMin(),
            $entity->getPhpMax(),
            $dependencies,
            $deprecated,
        );
    }
}
