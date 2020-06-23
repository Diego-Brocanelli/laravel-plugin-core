<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Templates\Compilers;

use Illuminate\Support\Facades\Blade;

trait Form
{
    use Base;

    public function registerForm()
    {
        // $this->registerDirective('formInput', 'compileFormInput');
    }
}
