<?php

declare(strict_types=1);

namespace App\Plugin\Core\Http\Controllers;

use App\Plugin\Core\Libraries\Panel\Entry;
use App\Plugin\Core\Libraries\Plugins\Handler;

class CoreController extends Controller
{
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
     * Essa rota devolve as informações iniciais para a camada de apresentação
     */
    public function home()
    {
        $this->pageTitle('Este é meu home');
        return vue('core::example-home');
    }

    /**
     * Essa rota devolve as informações iniciais para a camada de apresentação
     */
    public function page()
    {
        sleep(4);
        $this->pageTitle('Este é meu título nice');
        $this->breadCrumb()->append(new Entry('Página'));
        $this->sidebar()->append(new Entry('Carambolis'));
        return vue('core::example-page');
    }
}
