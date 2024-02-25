<?php

declare(strict_types=1);

use PhpCsFixerCustomFixers\Fixer\NoCommentedOutCodeFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocSelfAccessorFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesCommaSpacesFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocVarAnnotationToAssertFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitAssertArgumentsOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitDedicatedAssertFixer;
use PhpCsFixerCustomFixers\Fixer\PromotedConstructorPropertyFixer;
use PhpCsFixerCustomFixers\Fixer\StringableInterfaceFixer;

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP80Migration:risky' => true,
        '@PHP82Migration' => true,
        '@PHPUnit100Migration:risky' => true,
        'single_line_throw' => false,
        'class_definition' => ['single_item_single_line' => true],
        'phpdoc_to_param_type' => true,
        'phpdoc_to_property_type' => true,
        'phpdoc_to_return_type' => true,
        'nullable_type_declaration' => true,
        'phpdoc_line_span' => ['const' => 'single', 'method' => 'single', 'property' => 'single'],
        'phpdoc_param_order' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => true,
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
        ],
        PromotedConstructorPropertyFixer::name() => true,
        NoCommentedOutCodeFixer::name() => true,
        NoUselessParenthesisFixer::name() => true,
        NoUselessStrlenFixer::name() => true,
        PhpUnitAssertArgumentsOrderFixer::name() => true,
        PhpUnitDedicatedAssertFixer::name() => true,
        PhpdocSelfAccessorFixer::name() => true,
        PhpdocTypesCommaSpacesFixer::name() => true,
        PhpdocVarAnnotationToAssertFixer::name() => true,
        StringableInterfaceFixer::name() => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php-cs-fixer.cache')
    ;
