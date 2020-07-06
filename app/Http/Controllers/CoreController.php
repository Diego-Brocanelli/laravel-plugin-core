<?php

declare(strict_types=1);

namespace App\Plugin\Core\Http\Controllers;

use App\Plugin\Core\Libraries\Panel\Entry;
use App\Plugin\Core\Libraries\Plugins\Handler;

class CoreController extends Controller
{
    /**
     * Documento html da aplicação SPA
     */
    public function admin()
    {
        return view('core::app');
    }

    /**
     * Essa rota devolve as informações iniciais para a camada de apresentação
     */
    public function meta()
    {
        return [
            'meta' => Handler::instance()->metadata()
        ];
    }

    /**
     * Devolve os dados para comunicação do backend com a aplicação SPA
     */
    public function welcome()
    {
        // Força a página de boas vindas a ter o tema
        $this->changeTheme('core');

        $this->pageTitle('Bem vindo ao core da aplicação');

        $this->breadcrumb()
            ->append(new Entry('Home', '/admin'))
            ->append(new Entry('Boas Vindas', '/core/welcome'));

        return vue('core::welcome');
    }
}
