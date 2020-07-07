<?php

declare(strict_types=1);

namespace App\Plugin\Core\Providers;

use App\Exceptions\Handler;
use App\Plugin\Core\Libraries\Panel\Breadcrumb;
use App\Plugin\Core\Libraries\Panel\HeaderMenu;
use App\Plugin\Core\Libraries\Panel\Sidebar;
use App\Plugin\Core\Libraries\Plugins\Handler as PluginsHandler;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use InvalidArgumentException;

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
        if ($this instanceof ThemeServiceProvider) {
            $this->setupTheme();
            return $this;
        }

        $this->setupModule();
        return $this;
    }

    public function setupModule(): PluggableServiceProvider
    {
        PluginsHandler::instance()->registerPlugin($this->selfServiceProvider());

        $plugin              = PluginsHandler::instance()->plugin($this->selfServiceProvider());
        $pluginNamespace     = $plugin->config()->param('plugin_namespace');

        if ($pluginNamespace === null) {
            throw new \RuntimeException("O arquivo de configuração não contém o parâmetro 'plugin_namespace'");
        }

        $this->pluginPath    = $plugin->path();
        $this->namespaceTag  = Str::snake($pluginNamespace);
        $this->namespaceType = 'plugin';
        $this->assetsPath    = 'plugins';

        return $this;
    }

    public function setupTheme(): PluggableServiceProvider
    {
        PluginsHandler::instance()->registerTheme($this->selfServiceProvider());

        $theme              = PluginsHandler::instance()->theme($this->selfServiceProvider());
        $themeNamespace     = $theme->config()->param('theme_namespace');

        if ($themeNamespace === null) {
            throw new \RuntimeException("O arquivo de configuração não contém o parâmetro 'theme_namespace'");
        }

        $this->pluginPath    = $theme->path();
        $this->namespaceTag  = Str::snake($themeNamespace);
        $this->namespaceType = 'theme';
        $this->assetsPath    = 'themes';

        return $this;
    }

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

    protected function addScriptBottom(string $script): PluggableServiceProvider
    {
        $theme = PluginsHandler::instance()->theme($this->selfServiceProvider());
        if ($theme === null) {
            throw new InvalidArgumentException("Não é possível adicionar um asset em um plugin que não seja um tema");
        }
        $theme->addScriptBottom($script);

        return $this;
    }

    protected function addScriptTop(string $script): PluggableServiceProvider
    {
        $theme = PluginsHandler::instance()->theme($this->selfServiceProvider());
        if ($theme === null) {
            throw new InvalidArgumentException("Não é possível adicionar um asset em um plugin que não seja um tema");
        }
        $theme->addScriptTop($script);

        return $this;
    }

    protected function addStyle(string $style): PluggableServiceProvider
    {
        $theme = PluginsHandler::instance()->theme($this->selfServiceProvider());
        if ($theme === null) {
            throw new InvalidArgumentException("Não é possível adicionar um asset em um plugin que não seja um tema");
        }
        $theme->addStyle($style);

        return $this;
    }

    /**
     * Devolve o gerenciador de bradcrumbs.
     * 
     * @return Breadcrumb
     */
    protected function breadcrumb(): Breadcrumb
    {
        return Breadcrumb::instance();
    }

    /**
     * Devolve o gerenciador da sidebar.
     * 
     * @return Sidebar
     */
    protected function sidebar(): Sidebar
    {
        return Sidebar::instance();
    }

    /**
     * Devolve o gerenciador do menu do cabeçalho.
     * 
     * @return HeaderMenu
     */
    protected function headerMenu(): HeaderMenu
    {
        return HeaderMenu::instance();
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

    private function fileExists(string $filename)
    {
        return is_file($filename);
    }

    private function directoryExists(string $name)
    {
        return is_dir($name);
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
        $commands = "{$this->pluginPath}/routes/console.php";
        if ($this->app->runningInConsole() && $this->fileExists($commands)) {
            require $commands;
        }

        $publicPath = "{$this->pluginPath}/public";
        if ($this->directoryExists($publicPath)) {
            $this->publishes([
                $publicPath => public_path("{$this->assetsPath}/{$this->namespaceTag}"),
            ], "{$this->namespaceTag}-assets");
        }

        $configFile = "{$this->pluginPath}/config/{$this->namespaceType}_{$this->namespaceTag}.php";
        if ($this->fileExists($configFile)) {
            $this->publishes([
                $configFile => config_path("{$this->namespaceType}_{$this->namespaceTag}.php"),
            ], "{$this->namespaceTag}-config");
        }

        $factoriesPath = "{$this->pluginPath}/database/factories";
        $this->loadFactoriesFrom($factoriesPath);
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

        $routesWeb = "{$this->pluginPath}/routes/web.php";
        if ($this->fileExists($routesWeb)) {
            $this->loadRoutesFrom("{$this->pluginPath}/routes/web.php");
        }

        $routesApi = "{$this->pluginPath}/routes/api.php";
        if ($this->fileExists($routesApi)) {
            Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
                $this->loadRoutesFrom("{$this->pluginPath}/routes/api.php");
            });
        }

        // Nos templates do Blade as views do módulo devem ser utilizadas com prefixo.
        // Ao invés de @include('minha.linda.view'), 
        // deve-se usar @include('core::minha.linda.view')
        $viewsPath = "{$this->pluginPath}/resources/views/";
        if ($this->directoryExists($viewsPath)) {
            $this->loadViewsFrom($viewsPath, $this->namespaceTag);
        }

        $migrationsPath = "{$this->pluginPath}/database/migrations/";
        if ($this->directoryExists($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath, $this->namespaceTag);
        }

        $langsPath = "{$this->pluginPath}/resources/lang/";
        if ($this->directoryExists($langsPath)) {
            $this->loadTranslationsFrom($langsPath, $this->namespaceTag);
        }
    }
}
