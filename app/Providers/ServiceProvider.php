<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

/**
 * O serviceProvider é a forma que um pacote se comunicar com o projeto principal do Laravel.
 * Através dele é possivel personalizar o caminho das configurações, rotas, views e assets da 
 * aplicação, segmentando as funcionalidades num contexto delimitado.
 * 
 * Para mais informações sobre pacotes do Laravel,
 * leia https://laravel.com/docs/7.x/packages
 */
class ServiceProvider extends PluggableServiceProvider
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
        
        // $this->registerGlobalPluggable(function(){

        //     $entry = new Entry('Grade de Dados', 'example.grid', 'fas fa-book');
        //     Sidebar::instance()->append($entry);

        //     $entry = new Entry('Formulario', 'example.form', 'fas fa-book-open');
        //     Sidebar::instance()->append($entry);

        //     $entry = new Entry('Outras Entradas', '/', 'fas fa-cat');
        //     $entry->appendChild(new Entry('Opção 1'));
        //     $entry->appendChild(new Entry('Opção 2'));
        //     $entry->appendChild(new Entry('Opção 3'));
        //     Sidebar::instance()->append($entry);

        //     $entry = new Entry('Home', '/');
        //     Breadcrumb::instance()->append($entry);
        // });

        // $this->registerPluggable(function(){

        //     $entry = new Entry('Core', '/core/');
        //     Breadcrumb::instance()->append($entry);

        // });
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
