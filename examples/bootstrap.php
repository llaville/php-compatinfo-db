<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Infrastructure\Framework\Symfony\DependencyInjection\ContainerFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

return (new ContainerFactory())->create();
