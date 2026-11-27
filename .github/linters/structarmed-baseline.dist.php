<?php

declare(strict_types=1);

/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Baseline definition for production.
 *
 * It will allow to adopt StructArmed gradually from codebase of version 6.34.0 that still have 48 violations
 * 40 in Application layer
 * 8 in Domain layer
 *
 * @link https://github.com/llaville/php-compatinfo-db/tree/6.34
 * @link https://boundwize.github.io/structarmed/baselines/
 * @since Release 6.34.0
 * @author Laurent Laville
 */

return [
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler::__invoke()] has cyclomatic complexity of 9, maximum allowed is 5',
        'file' => 'src/Application/Command/Build/BuildHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler::__invoke()] is 69 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Build/BuildHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler::buildMethods()] has cyclomatic complexity of 8, maximum allowed is 5',
        'file' => 'src/Application/Command/Build/BuildHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler::buildMethods()] is 50 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Build/BuildHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler::buildClassConstants()] is 22 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Build/BuildHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Build\BuildHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Create\CreateHandler::__invoke()] is 28 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Create/CreateHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Create\CreateHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::__invoke()] has cyclomatic complexity of 6, maximum allowed is 5',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::__invoke()] is 34 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::buildDistribution()] has cyclomatic complexity of 20, maximum allowed is 5',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::buildDistribution()] is 157 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::buildExtension()] has cyclomatic complexity of 12, maximum allowed is 5',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::buildExtension()] is 85 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::useCoreReleases()] has cyclomatic complexity of 6, maximum allowed is 5',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Init\InitHandler::useCoreReleases()] is 33 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Init/InitHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Init\InitHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::__invoke()] has cyclomatic complexity of 16, maximum allowed is 5',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::__invoke()] is 93 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::analyse()] has cyclomatic complexity of 10, maximum allowed is 5',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::analyse()] is 54 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::updateReference()] has cyclomatic complexity of 10, maximum allowed is 5',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler::updateReference()] is 36 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Polyfill/PolyfillHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Polyfill\PolyfillHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler::addNewRelease()] has cyclomatic complexity of 6, maximum allowed is 5',
        'file' => 'src/Application/Command/Release/ReleaseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler::addNewRelease()] is 33 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Release/ReleaseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler::tagPhpMaxVersion()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Application/Command/Release/ReleaseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler::tagPhpMaxVersion()] is 42 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Release/ReleaseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler::componentDataProvider()] is 531 lines long, maximum allowed is 20',
        'file' => 'src/Application/Command/Release/ReleaseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Command\Release\ReleaseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher::__construct()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Application/Event/Dispatcher/EventDispatcher.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher::__construct()] is 35 lines long, maximum allowed is 20',
        'file' => 'src/Application/Event/Dispatcher/EventDispatcher.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Event\Dispatcher\EventDispatcher',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.layer.application_not_depend_infrastructure',
        'message' => 'Class [Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler] in layer [Application] must not depend on [Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements] which belongs to layer [Infrastructure]',
        'file' => 'src/Application/Query/Diagnose/DiagnoseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.layer.application_not_depend_infrastructure',
        'message' => 'Class [Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler] in layer [Application] must not depend on [Bartlett\CompatInfoDb\Infrastructure\RequirementsInterface] which belongs to layer [Infrastructure]',
        'file' => 'src/Application/Query/Diagnose/DiagnoseHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Diagnose\DiagnoseHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler::__invoke()] has cyclomatic complexity of 14, maximum allowed is 5',
        'file' => 'src/Application/Query/Doctor/DoctorHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler::__invoke()] is 85 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/Doctor/DoctorHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler::addDependency()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Application/Query/Doctor/DoctorHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler::addDependency()] is 36 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/Doctor/DoctorHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\Doctor\DoctorHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::__invoke()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::__invoke()] is 29 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::filterPlatformByExtensionInstalled()] is 21 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::filterPlatformByExtensionOutdated()] has cyclomatic complexity of 12, maximum allowed is 5',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::filterPlatformByExtensionOutdated()] is 47 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::filterPlatformByExtensionDeprecated()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.application_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler::filterPlatformByExtensionDeprecated()] is 23 lines long, maximum allowed is 20',
        'file' => 'src/Application/Query/ListRef/ListHandler.php',
        'class' => 'Bartlett\CompatInfoDb\Application\Query\ListRef\ListHandler',
        'layer' => 'Application',
    ],
    [
        'rule' => 'ddd.safety.domain_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait::getExtensionVersion()] has cyclomatic complexity of 27, maximum allowed is 5',
        'file' => 'src/Domain/Factory/ExtensionVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait::getExtensionVersion()] is 76 lines long, maximum allowed is 20',
        'file' => 'src/Domain/Factory/ExtensionVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait::getLatestPhpVersion()] has cyclomatic complexity of 16, maximum allowed is 5',
        'file' => 'src/Domain/Factory/ExtensionVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait::getLatestPhpVersion()] is 46 lines long, maximum allowed is 20',
        'file' => 'src/Domain/Factory/ExtensionVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait::getPrettyVersion()] has cyclomatic complexity of 34, maximum allowed is 5',
        'file' => 'src/Domain/Factory/LibraryVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait::getPrettyVersion()] is 126 lines long, maximum allowed is 20',
        'file' => 'src/Domain/Factory/LibraryVersionProviderTrait.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_complexity',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\ValueObject\Extension::__construct()] has cyclomatic complexity of 7, maximum allowed is 5',
        'file' => 'src/Domain/ValueObject/Extension.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\ValueObject\Extension',
        'layer' => 'Domain',
    ],
    [
        'rule' => 'ddd.safety.domain_max_method_length',
        'message' => 'Method [Bartlett\CompatInfoDb\Domain\ValueObject\Extension::__construct()] is 38 lines long, maximum allowed is 20',
        'file' => 'src/Domain/ValueObject/Extension.php',
        'class' => 'Bartlett\CompatInfoDb\Domain\ValueObject\Extension',
        'layer' => 'Domain',
    ],
];
