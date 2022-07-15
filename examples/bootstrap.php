<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Application\Kernel\ConsoleKernel;

require_once dirname(__DIR__) . '/config/bootstrap.php';

return (new ConsoleKernel('dev', true))->createFromConfigs([]);
