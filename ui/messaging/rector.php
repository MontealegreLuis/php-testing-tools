<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\SOLID\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\SOLID\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\SOLID\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
    ]);
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.4');
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        SetList::CODE_QUALITY,
        SetList::PHP_74,
        SetList::SOLID,
    ]);

    $parameters->set(Option::EXCLUDE_RECTORS, [
        UseInterfaceOverImplementationInConstructorRector::class,
        ExplicitBoolCompareRector::class,
        RepeatedLiteralToClassConstantRector::class,
        ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class,
        ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
    ]);
};
