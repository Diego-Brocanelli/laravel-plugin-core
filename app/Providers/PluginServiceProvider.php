<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

use App\Plugin\Core\Libraries\Plugins\Handler;

/**
 * O serviceProvider é a forma que um pacote se comunicar com o projeto principal do Laravel.
 * Através dele é possivel personalizar o caminho das configurações, rotas, views e assets da 
 * aplicação, segmentando as funcionalidades num contexto delimitado.
 * 
 * Para mais informações sobre pacotes do Laravel,
 * leia https://laravel.com/docs/7.x/packages
 */
abstract class PluginServiceProvider extends PluggableServiceProvider
{
    // ...
}
