<?php declare(strict_types=1);

/**
 * Handler to diagnose common errors in current platform.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Query\Diagnose;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements;
use Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface;

/**
 * @since Release 2.0.0RC1
 */
final class DiagnoseHandler implements QueryHandlerInterface
{
    /**
     * @param DiagnoseQuery $query
     * @return ProjectRequirements
     */
    public function __invoke(DiagnoseQuery $query): RequirementsInterface
    {
        return new ProjectRequirements($query);
    }
}
