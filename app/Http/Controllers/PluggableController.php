<?php

declare(strict_types=1);

namespace App\Plugin\Core\Http\Controllers;

use App\Plugin\Core\Libraries\Panel\Breadcrumb;
use App\Plugin\Core\Libraries\Panel\HeaderMenu;
use App\Plugin\Core\Libraries\Panel\Sidebar;
use App\Plugin\Core\Libraries\Plugins\Handler as PluginsHandler;
use App\Plugin\Core\Libraries\Plugins\Theme;
use App\Plugin\Core\Libraries\Templates\Handler as TemplatesHandler;
use App\Plugin\Core\Providers\ServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PluggableController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // Quando um controler é executado, o mecanismo de plugins é notificado
        // para poder desenhar os assets adequados na página HTML.
        PluginsHandler::instance()->setCurrentPlugin(ServiceProvider::class);
    }

    /**
     * Muda o tema utilizado em tempo de execução.
     * 
     * @param string $targetView
     * @param string $replacementView
     * @return BaseController
     */
    protected function changeTheme(string $theme): BaseController
    {
        PluginsHandler::instance()->setActiveTheme($theme);
        return $this;
    }

    protected function homeUrl(): string
    {
        return PluginsHandler::instance()->home();
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
    protected function headerMenu(): Sidebar
    {
        return HeaderMenu::instance();
    }

    /**
     * Devolve o gerenciador da sidebar.
     * 
     * @return Sidebar
     */
    protected function pageTitle(string $title): BaseController
    {
        PluginsHandler::instance()->setPageTitle($title);
        return $this;
    }

    /**
     * Devolve o tema atualmente em execução.
     * 
     * @return Theme
     */
    protected function theme(): Theme
    {
        return PluginsHandler::instance()->activeTheme();
    }

    public function callAction($method, $parameters)
    {
        $this->applyCurrentTemplates();
        return call_user_func_array([$this, $method], $parameters);
    }

    private function applyCurrentTemplates(): BaseController
    {
        TemplatesHandler::instance()->flush();

        $module = PluginsHandler::instance()->currentPlugin();
        $theme = PluginsHandler::instance()->activeTheme();

        // Executa as rotinas
        $globalRoutines = PluginsHandler::instance()->globalPluggables();
        $moduleRoutines = PluginsHandler::instance()->pluggables($module->tag());
        $themeRoutines = PluginsHandler::instance()->pluggables($theme->tag());

        array_walk($globalRoutines, fn ($func) => $func());
        array_walk($moduleRoutines, fn ($func) => $func());
        array_walk($themeRoutines, fn ($func) => $func());

        return $this;
    }
}
