<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Bartlett\CompatInfoDb\Domain\ValueObject\Platform as Domain;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform as Entity;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
final class PlatformHydrator implements HydratorInterface
{
    use HydrationArraysTrait;

    /**
     * @inheritDoc
     */
    public function extract(object $object): array
    {
        if (!$object instanceof Entity) {
            return [];
        }

        return [
            'description' => $object->getDescription(),
            'version' => $object->getVersion(),
            'created_at' => $object->getCreatedAt(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $data, string $class = Entity::class): object
    {
        $object = new $class();

        if (!$object instanceof Entity) {
            return $object;
        }

        $object->setDescription($data['description']);
        $object->setVersion($data['version']);
        $object->setCreatedAt($data['created_at']);
        $object->addExtensions($data['extensions']);

        return $object;
    }

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
