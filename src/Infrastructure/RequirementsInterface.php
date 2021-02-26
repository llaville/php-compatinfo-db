<?php declare(strict_types=1);

/**
 * Contract for project requirements.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Infrastructure;

use Symfony\Requirements\Requirement;

/**
 * @since Release 3.3.0
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
