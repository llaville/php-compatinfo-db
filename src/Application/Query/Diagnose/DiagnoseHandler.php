<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Diagnose;

use Bartlett\CompatInfoDb\Application\Query\QueryHandlerInterface;
use Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements;
use Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface;

/**
 * Handler to diagnose common errors in current platform.
 *
 * @since Release 2.0.0RC1
 * @author Laurent Laville
 */
final class DiagnoseHandler implements QueryHandlerInterface
{
    /**
     * @param DiagnoseQuery $query
     * @return RequirementsInterface
     */
    public function __invoke(DiagnoseQuery $query): RequirementsInterface
    {
        return new ProjectRequirements($query);
    }
}
