<?php

declare(strict_types=1);

// Atenção, os parâmetros de configuração devem seguir o padrão de nomenclatura do Laravel
// sempre em snake_case!!

return [

    'module_namespace' => 'Two', 
    
    'laravel_install_path' => realpath(__DIR__ . '/../../laravel'),
    
    'theme' => 'bnw/poc-plugins-arch-theme-01',

    'scripts' => [
        'shorenaitis.js',
        'birineiders.js',
    ],

    'styles' => [
        'shoooo.css',
    ],

    'templates' => [
        'header' => 'theme-one::header',
        'sidebar-left' => 'theme-one::sidebar-left',
    ],
];
