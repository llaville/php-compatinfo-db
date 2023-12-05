<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Dependency as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Dependency as Entity;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
class DependencyHydrator implements HydratorInterface
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
            'constraint' => $object->getConstraintExpression(),
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
        $object->setConstraintExpression($data['constraint']);

        return $object;
    }

    public function toDomain(Entity $entity): Domain
    {
        return new Domain(
            $entity->getName(),
            $entity->getConstraintExpression()
        );
    }
}
