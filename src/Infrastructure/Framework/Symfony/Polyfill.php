<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony;

use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

/**
 * Keep BC between Symfony 4.4 and Symfony 5.x
 *
 * @since 3.1.1
 * @author Laurent Laville
 * @see https://github.com/llaville/php-compatinfo-db/issues/61
 */
final class Polyfill
{
    /**
     * Creates a reference to a service.
     *
     * @param string $serviceId
     * @return ReferenceConfigurator
     */
    public static function service(string $serviceId): ReferenceConfigurator
    {
        return new ReferenceConfigurator($serviceId);
    }
}

function service(string $serviceId): ReferenceConfigurator
{
    return Polyfill::service($serviceId);
}
