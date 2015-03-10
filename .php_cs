<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/spec');

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(array(
        'header_comment',
        'align_equals',
        'concat_with_spaces',
        'ordered_use',
        'phpdoc_order',
        'strict',
        'strict_param',
    ))
    ->finder($finder);
