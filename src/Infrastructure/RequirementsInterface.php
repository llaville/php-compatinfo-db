<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure;

use Symfony\Requirements\Requirement;

/**
 * Contract for project requirements.
 *
 * @since Release 3.3.0
 * @author Laurent Laville
 */
interface RequirementsInterface
{
    /**
     * Returns the PHP configuration file (php.ini) path.
     *
     * @return false|string php.ini file path
     */
    public function getPhpIniPath();

    /**
     * Returns all mandatory requirements.
     *
     * @return Requirement[]
     */
    public function getRequirements();
}
