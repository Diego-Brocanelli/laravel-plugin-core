<?php

declare(strict_types=1);

// Atenção, os parâmetros de configuração devem seguir o padrão de nomenclatura do Laravel
// sempre em snake_case!!

return [

    'module_namespace' => 'One', 
    
    'laravel_install_path' => realpath(__DIR__ . '/../../laravel'),
    
    'theme' => 'bnw/poc-plugins-arch-theme-01',

    'scripts' => [
        'module.js',
    ],

    'scripts_top' => [
        'legal.js',
    ],

    'styles' => [
        'module.css',
    ],

    'templates' => [
        'admin-body' => 'theme-one::admin',
        'header' => 'theme-one::header',
        'sidebar-left' => 'theme-one::sidebar-left',
        'sidebar-right' => 'theme-one::sidebar-right',
        'footer' => 'theme-one::footer',
    ],
];
