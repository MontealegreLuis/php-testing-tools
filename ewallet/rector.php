<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests/src/Alice',
        __DIR__ . '/tests/src/Behat',
        __DIR__ . '/tests/src/DataBuilders',
        __DIR__ . '/tests/src/Doctrine',
        __DIR__ . '/tests/src/Fakes',
        __DIR__ . '/tests/src/PhpSpec',
        __DIR__ . '/tests/src/PHPUnit',
        __DIR__ . '/tests/src/RabbitMq',
        __DIR__ . '/tests/src/Setup',
    ]);
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.4');
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [SetList::CODE_QUALITY]);

    $parameters->set(Option::EXCLUDE_RECTORS, [
        UseInterfaceOverImplementationInConstructorRector::class,
        ExplicitBoolCompareRector::class
    ]);
};
