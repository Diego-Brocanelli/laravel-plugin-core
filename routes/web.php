<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Plugin\Core\Http\Controllers')->group(function () {

    // Contém a única view da aplicação, que desenha a página html do SPA
    Route::get('/admin', 'CoreController@admin')->name('example.admin');

    // Devolve os dados para comunicação do backend com a aplicação SPA
    Route::get('/core/meta', 'CoreController@meta')->name('core.meta');

    // Quando nenhuma página home for setada, a página de boasvindas será exibida
    Route::get('/core/welcome', 'CoreController@welcome')->name('core.welcome');
});
