<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

use App\Plugin\Core\Libraries\Plugins\Handler as PluginsHandler;
use Closure;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;

/**
 * O serviceProvider é a forma que um pacote se comunicar com o projeto principal do Laravel.
 * Através dele é possivel personalizar o caminho das configurações, rotas, views e assets da 
 * aplicação, segmentando as funcionalidades num contexto delimitado.
 * 
 * Para mais informações sobre pacotes do Laravel,
 * leia https://laravel.com/docs/7.x/packages
 */
abstract class PluggableServiceProvider extends BaseServiceProvider
{
    protected $assetsPath;

    protected $namespaceTag;

    protected $namespaceType;

    protected $pluginTheme;

    protected $pluginPath;

    public function setupPlugin(): PluggableServiceProvider
    {
        PluginsHandler::instance()->registerPlugin($this->selfServiceProvider());

        $plugin              = PluginsHandler::instance()->plugin($this->selfServiceProvider());
        $pluginNamespace     = $plugin->config()->param('plugin_namespace');
        $this->namespaceTag  = Str::snake($pluginNamespace);
        $this->namespaceType = 'plugin';
        $this->assetsPath    = 'plugins';
        $this->pluginPath    = $plugin->path();

        return $this;
    }

    // /**
    //  * Registra uma rotina que será invocada para qualquer plugin em execução.
    //  * 
    //  * @param Closure $func 
    //  * @return Handler
    //  */
    // public function registerGlobalPluggable(Closure $func): PluggableServiceProvider
    // {
    //     PluginsHandler::instance()->registerGlobalPluggable($func);
    //     return $this;
    // }

    // /**
    //  * Registra uma rotina que será invocada apenas quando um plugin específigo
    //  * for executado.
    //  * 
    //  * @param Closure $func 
    //  * @return Handler
    //  */
    // public function registerPluggable(Closure $func): PluggableServiceProvider
    // {
    //     PluginsHandler::instance()->registerPluggable($this->selfServiceProvider(), $func);
    //     return $this;
    // }

    // /**
    //  * Muda uma view previamente registrada do Laravel.
    //  * 
    //  * @param string $targetView
    //  * @param string $replacementView
    //  * @return PluggableServiceProvider
    //  */
    // protected function changeView(string $targetView, string $replacementView): PluggableServiceProvider
    // {
    //     if ($this->namespacePrefix === 'module') {
    //         PluginsHandler::instance()->module($this->selfServiceProvider())
    //             ->addTemplateView($targetView, $replacementView);
    //         return $this;
    //     }

    //     PluginsHandler::instance()->theme($this->selfServiceProvider())
    //         ->addTemplateView($targetView, $replacementView);
    //     return $this;
    // }

    /**
     * Muda o tema utilizado para todas as rotas deste plugin somente.
     * 
     * @param string $targetView
     * @param string $replacementView
     * @return BaseController
     */
    protected function changeTheme(string $theme): PluggableServiceProvider
    {
        $this->pluginTheme = $theme;
        return $this;
    }

    /**
     * Devolve o nome real do ServiceProvider invocado.
     * 
     * @return string
     */
    protected function selfServiceProvider(): string
    {
        return get_called_class();
    }

    /**
     * Este método é invocado pelo Laravel apenas após todos os pacotes serem registrados.
     * Veja o método register().
     * 
     * Aqui pode-se implementar tratativas específicas do pacote em questão, como invocação de 
     * classes que só existem no pacote, ou utilização de classes provenientes de outros 
     * módulos de dependência.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            require "{$this->pluginPath}/routes/console.php";
        }

        // Arquivos publicados pelo artisan:
        // Ex: php artisan vendor:publish --tag=modules --force
        $this->publishes([
            "{$this->pluginPath}/public" => public_path("{$this->assetsPath}/{$this->namespaceTag}"),
        ], "assets-{$this->namespaceTag}");
        
        $this->publishes([
            "{$this->pluginPath}/config/{$this->namespaceType}_{$this->namespaceTag}.php" => config_path("{$this->namespaceType}_{$this->namespaceTag}.php"),
        ], "{$this->namespaceType}-{$this->namespaceTag}");
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
        $this->setupPlugin();

        // O 'mergeConfigFrom' junta os valores do arquivo de configuração disponíveis no módulo
        // com o o arquivo de mesmo nome, publicado no projeto principal do Laravel
        // para que não existam inconsistencias ou ausência de parâmetros usados pelo módulo
        $this->mergeConfigFrom("{$this->pluginPath}/config/{$this->namespaceType}_{$this->namespaceTag}.php", "{$this->namespaceType}_{$this->namespaceTag}");

        $this->loadRoutesFrom("{$this->pluginPath}/routes/web.php");
        $this->loadRoutesFrom("{$this->pluginPath}/routes/api.php");

        // Nos templates do Blade as views do módulo devem ser utilizadas com prefixo.
        // Ao invés de @include('minha.linda.view'), 
        // deve-se usar @include('core::minha.linda.view')
        $this->loadViewsFrom("{$this->pluginPath}/resources/views/", $this->namespaceTag);
        
        $this->loadMigrationsFrom("{$this->pluginPath}/database/migrations/", $this->namespaceTag);
        $this->loadTranslationsFrom("{$this->pluginPath}/resources/lang/", $this->namespaceTag);
    }
}
