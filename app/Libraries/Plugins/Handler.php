<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

use App\Plugin\Core\Libraries\Composer\Parser;
use App\Plugin\Core\Libraries\Panel\Breadcrumb;
use App\Plugin\Core\Libraries\Panel\HeaderMenu;
use App\Plugin\Core\Libraries\Panel\Sidebar;
use App\Plugin\Core\Libraries\Panel\UserData;
use Closure;
use Exception;
use InvalidArgumentException;
use ReflectionException;
use Illuminate\Support\ServiceProvider;

/**
 * Esta é a classe que permite acesso as todos os plugins registrados na aplicação.
 * Ela funciona como uma API, para que qualquer plugin seja acessível de forma direta.
 */
class Handler
{
    static $instance;

    private $homePage;

    private $pageTitle;

    private $sidebarLeftStatus = 'enabled';

    private $sidebarRightStatus = 'disabled';

    private $plugins = [];

    private $themes = [];

    private $pluginsMap = [];

    private $themesMap = [];

    private $lastPlugin;

    private $lastTheme;

    private $currentPlugin;

    private $activeTheme;

    private $assets;

    private $globalPluggablesRoutines = [];

    private $pluggablesRoutines = [];

    private function __construct()
    {
        // acesso somente através do singleton
    }

    public static function instance(): Handler
    {
        if (static::$instance === null) {
            static::$instance = new Handler();
        }

        return static::$instance;
    }

    /**
     * Zera as informações do manupulador de plugins.
     * Isso é utilizado, principalemnet, para efetuar testes de unidade 
     * sem interferência de rotinas anteriormente executadas
     */
    public function flush(): Handler
    {
        $this->homePage                 = null;
        $this->pageTitle                = null;
        $this->sidebarLeftStatus        = 'enabled';
        $this->sidebarRightStatus       = 'disabled';
        $this->plugins                  = [];
        $this->themes                   = [];
        $this->pluginsMap               = [];
        $this->themesMap                = [];
        $this->lastPlugin               = null;
        $this->lastTheme                = null;
        $this->currentPlugin            = null;
        $this->activeTheme              = null;
        $this->assets                   = null;
        $this->globalPluggablesRoutines = [];
        $this->modulePluggables         = [];
        return $this;
    }

    /**
     * Os assets são resolvidos sempre que o estado de algum plugin ou tema mudar.
     * Este método é responsável por forçar a nova resolução!
     */
    private function flushAssets(): Handler
    {
        $this->assets = null;
        return $this;
    }

    private function getProviderPath(string $serviceProviderClass)
    {
        try {
            $reflect = new \ReflectionClass($serviceProviderClass);
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException("O ServiceProvider {$serviceProviderClass} não existe");
        }

        if ($reflect->isSubclassOf(ServiceProvider::class) === false) {
            throw new InvalidArgumentException(
                "O ServiceProvider {$serviceProviderClass} é inválido, " .
                    "pois não implementa Illuminate\Support\ServiceProvider"
            );
        }

        return realpath(dirname($reflect->getFilename()) . "/../../");
    }

    private function getPluginName(array $composerParams)
    {
        foreach ($composerParams as $param => $value) {
            if (
                strpos($param, 'autoload.psr_4.app_plugin') !== false
                || strpos($param, 'autoload.psr_4.app_theme') !== false
            ) {
                $nodes = explode('/', $value);
                return end($nodes);
            }
        }

        throw new Exception(
            'O parâmetro autoload.psr-4.App\\Plugin\\XX ' .
                'do composer.json deve conter o namespace como último nó da cadeia'
        );
    }

    /**
     * Seta a página inicial a ser utilizada pelo painel.
     * 
     * @param string $url 
     */
    public function setHomePage(string $url): Handler
    {
        $this->homePage = $url;
        return $this;
    }

    /**
     * Obtém a url para a página inicial do painel.
     * 
     * @return string
     */
    public function home(): string
    {
        return $this->homePage ?? '/core/welcome';
    }

    /**
     * Seta o título da página atual.
     * 
     * @param string $title 
     */
    public function setPageTitle(string $title): Handler
    {
        $this->pageTitle = $title;
        return $this;
    }

    /**
     * Obtém o título da página atual.
     * 
     * @return string
     */
    public function title(): string
    {
        return $this->pageTitle ?? 'Página';
    }

    public function enableSidebarLeft(): string
    {
        return $this->sidebarLeftStatus = 'enabled';
    }

    public function disableSidebarLeft(): string
    {
        return $this->sidebarLeftStatus = 'disabled';
    }

    public function sidebarLeftStatus(): string
    {
        return $this->sidebarLeftStatus;
    }

    public function enableSidebarRight(): string
    {
        return $this->sidebarRightStatus = 'enabled';
    }

    public function disableSidebarRight(): string
    {
        return $this->sidebarRightStatus = 'disabled';
    }

    public function sidebarRightStatus(): string
    {
        return $this->sidebarRightStatus;
    }

    /**
     * Registra o módulo para ser encontrado pelo mecanismo posteriormente.
     * 
     * @param string $serviceProvider
     */
    public function registerPlugin(string $serviceProviderClass): Handler
    {
        // não é possível registrar o mesmo plugin duas vezes
        if (isset($this->pluginsMap[$serviceProviderClass]) === true) {
            return $this;
        }

        $path = $this->getProviderPath($serviceProviderClass);

        $composerParams = new Parser("{$path}/composer.json");

        $name = $this->getPluginName($composerParams->all());

        $plugin = new Plugin($name, $path, [$serviceProviderClass]);

        $this->plugins[$plugin->tag()] = $plugin;
        $this->pluginsMap[$serviceProviderClass] = $plugin->tag();
        $this->lastPlugin = $plugin->tag();

        return $this;
    }

    /**
     * Registra o tema para ser encontrado pelo mecanismo posteriormente.
     * 
     * @param string $serviceProvider
     */
    public function registerTheme(string $serviceProviderClass): Handler
    {
        // não é possível registrar o mesmo tema duas vezes
        if (isset($this->themesMap[$serviceProviderClass]) === true) {
            return $this;
        }

        $path = $this->getProviderPath($serviceProviderClass);

        $composerParams = new Parser("{$path}/composer.json");

        $name = $this->getPluginName($composerParams->all());

        $theme = new Theme($name, $path, [$serviceProviderClass]);

        $this->themes[$theme->tag()] = $theme;
        $this->themesMap[$serviceProviderClass] = $theme->tag();
        $this->lastTheme = $theme->tag();

        // O primeiro tema registrado é marcado como ativo por padrão
        if ($this->activeTheme === null) {
            $this->setActiveTheme($theme->tag());
        }

        return $this;
    }

    public function lastPlugin(): ?Plugin
    {
        return $this->plugins[$this->lastPlugin] ?? null;
    }

    public function lastTheme(): ?Theme
    {
        return $this->themes[$this->lastTheme] ?? null;
    }

    public function plugin(string $id): ?Plugin
    {
        // se a identificação for o nome do ServiceProvider
        if (isset($this->pluginsMap[$id]) === true) {
            $id = $this->pluginsMap[$id];
        }

        return $this->plugins[$id] ?? null;
    }

    public function theme(string $id): ?Theme
    {
        // se a identificação for o nome do ServiceProvider
        if (isset($this->themesMap[$id]) === true) {
            $id = $this->themesMap[$id];
        }

        return $this->themes[$id] ?? null;
    }

    public function allPlugins(): array
    {
        return $this->plugins;
    }

    public function allThemes(): array
    {
        return $this->themes;
    }

    /**
     * Determina qual é o módulo atualmente em execução.
     * 
     * @param string $id
     */
    public function setCurrentPlugin(string $id): Handler
    {
        // se a identificação for o nome do ServiceProvider
        if (isset($this->pluginsMap[$id]) === true) {
            $id = $this->pluginsMap[$id];
        }

        if (isset($this->plugins[$id]) === false) {
            throw new Exception("O módulo {$id} especificado não foi encontrado no registro");
        }

        $this->currentPlugin = $id;
        $this->flushAssets();
        return $this;
    }

    public function currentPlugin(): ?Plugin
    {
        return $this->plugins[$this->currentPlugin] ?? null;
    }

    /**
     * Determina qual é o tema ativo.
     * 
     * @param string $id
     */
    public function setActiveTheme(string $id): Handler
    {
        if ($id === 'core') {
            $this->flushAssets();
            $this->activeTheme = $id;
            return $this;
        }

        // se a identificação for o nome do ServiceProvider
        if (isset($this->themesMap[$id]) === true) {
            $id = $this->themesMap[$id];
        }

        if (isset($this->themes[$id]) === false) {
            throw new Exception("O tema especificado não foi encontrado no registro");
        }

        $this->flushAssets();
        $this->activeTheme = $id;
        return $this;
    }

    public function activeTheme(): ?Theme
    {
        return $this->themes[$this->activeTheme] ?? ThemeCore::factory();
    }

    /**
     * Registra uma rotina que será invocada para qualquer plugin em execução.
     * 
     * @param Closure $func 
     * @return Handler
     */
    public function registerGlobalPluggable(Closure $func): Handler
    {
        $this->globalPluggablesRoutines[] = $func;
        return $this;
    }

    /**
     * Registra uma rotina que será invocada apenas quando um plugin específigo
     * for executado.
     * 
     * @param Closure $func 
     * @return Handler
     */
    public function registerPluggable(string $tag, Closure $func): Handler
    {
        // se a identificação for o nome do ServiceProvider
        if (isset($this->themesMap[$tag]) === true) {
            $tag = $this->themesMap[$tag];
        }

        if (isset($this->pluginsMap[$tag]) === true) {
            $tag = $this->pluginsMap[$tag];
        }

        if (isset($this->plugins[$tag]) === false && isset($this->themes[$tag]) === false) {
            throw new Exception("Para plugar uma rotina, é necessário que a tag seja um módulo ou um tema previamente registrado");
        }

        $this->pluggablesRoutines[] = $func;
        return $this;
    }

    public function globalPluggables()
    {
        return $this->globalPluggablesRoutines;
    }

    public function pluggables(string $tag)
    {
        // se a identificação for o nome do ServiceProvider
        if (isset($this->themesMap[$tag]) === true) {
            $tag = $this->themesMap[$tag];
        }

        if (isset($this->pluginsMap[$tag]) === true) {
            $tag = $this->pluginsMap[$tag];
        }

        if (isset($this->plugins[$tag]) === false && isset($this->themes[$tag]) === false) {
            throw new Exception("A tag especificada não pertence a nenhum módulo ou um tema previamente registrado");
        }

        return $this->pluggablesRoutines;
    }

    protected function resolveAssets(): array
    {
        if ($this->assets === null) {

            $assets = [
                'scripts_top'    => [],
                'scripts_bottom' => [],
                'scripts'        => [],
                'styles'         => [],
            ];

            // Os assets principais sempre estão presentes.
            // Entre eles se concontra: Bootstrap4, SweetAlert, Axios, etc
            // $assets['scripts_top'][] = '/plugins/core/js/core.js';
            // $assets['styles'][]  = '/plugins/core/css/core.css';

            // Os assets do tema servem para adaptar a aparência do
            // sistema como um todo, modificando o Bootstrap4 e
            // os componentes adicionais como o SweetAlert, por exemplo
            $theme = $this->activeTheme();
            $assets['scripts_top']    = array_merge($assets['scripts_top'], array_values($theme->scriptsTop()));
            $assets['scripts_bottom'] = array_merge($assets['scripts_bottom'], array_values($theme->scriptsBottom()));
            $assets['styles']         = array_merge($assets['styles'], array_values($theme->styles()));

            // Por último, são acrregados os assets do módulo em execução,
            // para que seja possível ao módulo modificar algum script ou estilo
            // proveniente do Core ou do Tema
            $module = $this->currentPlugin();
            if ($module !== null) {
                $assets['scripts_top']    = array_merge($assets['scripts_top'], array_values($module->scriptsTop()));
                $assets['scripts_bottom'] = array_merge($assets['scripts_bottom'], array_values($module->scriptsBottom()));
                $assets['styles']         = array_merge($assets['styles'], array_values($module->styles()));
            }

            $assets['scripts'] = array_merge($assets['scripts_top'], $assets['scripts_bottom']);

            $this->assets = $assets;
        }

        return $this->assets;
    }

    public function scriptsTop()
    {
        $assets = $this->resolveAssets();
        return $assets['scripts_top'] ?? [];
    }

    public function scriptsBottom()
    {
        $assets = $this->resolveAssets();
        return $assets['scripts_bottom'] ?? [];
    }

    public function scripts()
    {
        $assets = $this->resolveAssets();
        return $assets['scripts'] ?? [];
    }

    public function styles()
    {
        $assets = $this->resolveAssets();
        return $assets['styles'] ?? [];
    }

    private function version()
    {
        return file_get_contents(__DIR__ . '/../../../version');
    }

    /**
     * Devolve as informações de comunicação com a camada de apresentação
     * @return array
     */
    public function metadata(): array
    {
        $assets = $this->resolveAssets();
        return [
            'version'              => $this->version(),
            'home_url'             => $this->home(),
            'page_title'           => $this->title(),
            'user_data'            => UserData::instance()->toArray(),
            'scripts'              => $assets['scripts'] ?? [],
            'styles'               => $assets['styles'] ?? [],
            'header_menu'          => HeaderMenu::instance()->toArray(),
            'sidebar_left'         => Sidebar::instance()->toArray(),
            'breadcrumb'           => Breadcrumb::instance()->toArray(),
            'sidebar_left_status'  => $this->sidebarLeftStatus(),
            'sidebar_right_status' => $this->sidebarRightStatus(),
        ];
    }
}
