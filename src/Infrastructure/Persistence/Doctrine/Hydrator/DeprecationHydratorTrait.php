<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator;

use Deprecated;

/**
 * @since Release 6.12.0
 * @author Laurent Laville
 */
trait DeprecationHydratorTrait
{
    /**
     * @param string|array<string, string> $data
     */
    public function hydrateDeprecation(string|array $data, object $object): void
    {
        if (is_string($data)) {
            // accept legacy format
            $since = $data;
            $message = null;
        } else {
            // accept new enhanced format -- array{since?: string, message?: string}
            $since = $data['since'] ?? null;
            $message = $data['message'] ?? null;
        }

        $object->setDeprecated(new Deprecated($message, $since));
    }
}
