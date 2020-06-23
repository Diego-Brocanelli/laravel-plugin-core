<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Templates;

use Illuminate\View\Factory;

class ViewFactory extends Factory
{
    protected function normalizeName($name)
    {
        $name = Handler::instance()->resolveExtendsCore($name);
        return parent::normalizeName($name);
    }

    // protected function viewInstance($view, $path, $data)
    // {
    //     return new View($this, $this->getEngineFromPath($path), $view, $path, $data);
    // }
}
