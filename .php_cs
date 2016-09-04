<?php

$finder = (new Symfony\Component\Finder\Finder())
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/spec');

return Symfony\CS\Config::create()
    ->fixers([
        'align_double_arrow',
        'align_equals',
        'concat_with_spaces',
        'header_comment',
        'logical_not_operators_with_successor_space',
        'multiline_spaces_before_semicolon',
        'ordered_use',
        'phpdoc_order',
        'strict',
        'strict_param',
    ])
    ->finder($finder);
