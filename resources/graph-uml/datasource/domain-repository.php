<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @since Release 3.17.0
 * @author Laurent Laville
 */

use Bartlett\CompatInfoDb\Domain\Repository\ClassRepository;
use Bartlett\CompatInfoDb\Domain\Repository\ConstantRepository;
use Bartlett\CompatInfoDb\Domain\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\EntityManagerTrait;
use Bartlett\CompatInfoDb\Domain\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\FunctionRepository;
use Bartlett\CompatInfoDb\Domain\Repository\RepositoryInterface;

function dataSource(): Generator
{
    $classes = [
        ClassRepository::class,
        ConstantRepository::class,
        DistributionRepository::class,
        EntityManagerTrait::class,
        ExtensionRepository::class,
        FunctionRepository::class,
        FunctionRepository::class,
        RepositoryInterface::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
