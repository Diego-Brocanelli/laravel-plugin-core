<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

use App\Plugin\Core\Libraries\Panel\Entry;

/**
 * O serviceProvider é a forma que um pacote se comunicar com o projeto principal do Laravel.
 * Através dele é possivel personalizar o caminho das configurações, rotas, views e assets da 
 * aplicação, segmentando as funcionalidades num contexto delimitado.
 * 
 * Para mais informações sobre pacotes do Laravel,
 * leia https://laravel.com/docs/7.x/packages
 */
class ServiceProvider extends PluginServiceProvider
{
    /**
     * Este método é invocado pelo Laravel apenas após todos os pacotes serem registrados.
     * Veja o método register().
     * 
     * Aqui pode-se implementar tratativas específicas do pacote em questão, como invocação de 
     * classes que só existem no pacote, ou utilização de classes provenientes de outros 
     * plugins de dependência.
     */
    public function boot()
    {
        parent::boot();
        
        $this->breadcrumb()
            ->append(new Entry('Home', '/admin'))
            ->append(new Entry('Paginas', '/pages'));

        $entry = (new Entry('Terceiro', '/page'))
            ->appendChild(new Entry('Quarto', '/aaa'))
            ->appendChild((new Entry('Quinto', '/bbb'))->setStatus(Entry::STATUS_ACTIVE))
            ->appendChild((new Entry('Sexto', '/ccc'))->setStatus(Entry::STATUS_DISABLED));

        $this->sidebar()
            ->append(new Entry('Primeiro', '/admin'))
            ->append(new Entry('Segundo', '/page'))
            ->append($entry);
    }

    /**
     * Este método é invocado pelo Laravel no momento que o módulo é carregado.
     * Neste momento, o Kernel estará carregando todos os módulos disponíveis no diretório 
     * vendor e executando seus respectivos métodos register(). 
     * 
     * IMPORTANTE: Não coloque implementações que dependam de outros módulos neste método!
     * Como o laravel carregará os módulos de forma automatizada, não é possível determinar 
     * a ordem de execução!!
     */
    public function register()
    {
        // Este é o ServiceProvider principal deste pacote.
        // Para que o mecanismo de plugins possa gerenciá-lo, é preciso
        // registrá-lo como módulo.
        // -----------------------------------------------------------------------
        // Obs: é normal, em alguns casos, a existência de mais de um 
        // ServiceProvider em um mesmo pacote. Neste caso, apenas um
        // deverão ser registrado como o principal, responsável por 
        // dar visibilidade ao módulo dentro do mecanismo de plugins

        parent::register();

    //     // $this->app->singleton('sidebar', function ($app) {
    //     //     return Sidebar::instance();
    //     // });

    //     // $this->app->singleton('breadcrumb', function ($app) {
    //     //     return Breadcrumb::instance();
    //     // });
    }
}
