<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

use App\Plugin\Core\Libraries\Panel\Entry;
use App\Plugin\Core\Libraries\Panel\UserData;

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

        // Além dos assets padrões do plugin, o core
        // também possui um tema padrão
        $this->publishes([
            "{$this->pluginPath}/public" => public_path("themes/core"),
        ], "assets-core-theme");

        // Dados abaixo são de exemplo

        UserData::instance()
            ->setName('Claire Redfield')
            ->setLogin('claire@residentevil.com.br')
            ->setPicture('http://lorempixel.com/25/25/people/9/');


        $this->breadcrumb()
            ->append(new Entry('Home', '/core/home'))
            ->append(new Entry('Paginas', '/core/page', 'exclamation-circle-fill'));

        $entry = (new Entry('Terceiro', '/core/page'))
            ->appendChild(new Entry('Quarto', '/aaa'))
            ->appendChild((new Entry('Quinto', '/bbb'))->setStatus(Entry::STATUS_ACTIVE))
            ->appendChild((new Entry('Sexto', '/ccc'))->setStatus(Entry::STATUS_DISABLED));

        $this->sidebar()
            ->append(new Entry('Primeiro', '/core/page', 'exclamation-circle-fill'))
            ->append(new Entry('Segundo', '/core/page'))
            ->append(new Entry('Grid', '/core/grid'))
            ->append(new Entry('Form', '/core/form'))
            ->append($entry);

        $this->headerMenu()
            ->append(new Entry('Primeiro', '/core/page', 'exclamation-circle-fill'))
            ->append(new Entry('Segundo', '/core/page'))
            ->append((new Entry('Sep'))->setType(Entry::TYPE_SEPARATOR))
            ->append((new Entry('Quinto', '/core/form'))->setStatus(Entry::STATUS_ACTIVE))
            ->append((new Entry('Sexto', '/core/grid'))->setStatus(Entry::STATUS_DISABLED));
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
    }
}
