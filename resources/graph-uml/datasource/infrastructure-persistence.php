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

use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Class_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\ClassRelationship;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Constant_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\ConstantRelationship;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Dependency;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Extension;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\ExtVersionTrait;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Function_;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\FunctionRelationship;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\IniEntry;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\IniRelationship;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\PhpVersionTrait;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Platform;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\PrimaryIdentifierTrait;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Relationship;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity\Release;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ClassHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ConstantHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\DependencyHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ExtensionHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\FunctionHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\HydratorInterface;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\IniEntryHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\PlatformHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Hydrator\ReleaseHydrator;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ClassRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ConstantRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\DistributionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\ExtensionRepository;
use Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Repository\FunctionRepository;

function dataSource(): Generator
{
    $classes = [
        // Doctrine Entity
        Class_::class,
        ClassRelationship::class,
        Constant_::class,
        ConstantRelationship::class,
        Dependency::class,
        Extension::class,
        ExtVersionTrait::class,
        Function_::class,
        FunctionRelationship::class,
        IniEntry::class,
        IniRelationship::class,
        PhpVersionTrait::class,
        Platform::class,
        PrimaryIdentifierTrait::class,
        Relationship::class,
        Release::class,
        // Doctrine Hydrator
        ClassHydrator::class,
        ConstantHydrator::class,
        DependencyHydrator::class,
        ExtensionHydrator::class,
        FunctionHydrator::class,
        HydratorInterface::class,
        IniEntryHydrator::class,
        PlatformHydrator::class,
        ReleaseHydrator::class,
        // Doctrine Repository
        ClassRepository::class,
        ConstantRepository::class,
        DistributionRepository::class,
        ExtensionRepository::class,
        FunctionRepository::class,
        EntityManagerFactory::class,
    ];
    foreach ($classes as $class) {
        yield $class;
    }
}
