<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src']);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'no_unused_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_blank_line_before_namespace' => true,
        'ordered_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline_array' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'array_indentation' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'no_whitespace_in_blank_line' => true,
        'declare_strict_types' => true,
        'class_attributes_separation' => ['elements' => ['method', 'property']],
        'header_comment' => [
            'commentType' => 'PHPDoc',
            'header' => 'PHP version 7.2

This source file is subject to the license that is bundled with this package in the file LICENSE.',
            'separate' => 'bottom',
            'location' => 'after_declare_strict',
        ],
        'single_blank_line_at_eof' => true,
        'cast_spaces' => ['space' => 'single'],
        'not_operator_with_successor_space' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'return_type_declaration' => ['space_before' => 'none'],
        'modernize_types_casting' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
