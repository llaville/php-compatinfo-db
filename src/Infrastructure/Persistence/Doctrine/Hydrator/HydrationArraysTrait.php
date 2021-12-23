<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

/**
 * @since Release 3.0.0
 * @author Laurent Laville
 */
trait HydrationArraysTrait
{
    /**
     * {@inheritDoc}
     */
    public function hydrateArrays(array $data): array
    {
        $valueObjects = [];

        foreach ($data as $values) {
            $valueObjects[] = $this->hydrate($values);
        }

        return $valueObjects;
    }
}
