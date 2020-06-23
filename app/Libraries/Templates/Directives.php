<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Templates;

use App\Plugin\Core\Libraries\Templates\Compilers\Base;
use App\Plugin\Core\Libraries\Templates\Compilers\Form;
use Illuminate\Support\Facades\Blade;

/**
 * Esta é a classe contem os helpers usados para compilar diretivas 
 * do Laravel personalizadas.
 */
class Directives
{
    use Base;
    use Form;

    private $directivesList = [];

    public function boot()
    {
        $this->registerDirective('coreTest', 'compileTest');

        foreach($this->directivesList as $name => $method) {
            Blade::directive($name, Directives::class . "::{$method}");
        }
    }

    protected function compileTest($expression)
    {
        return "<?php echo {$expression}; ?>";
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([new self(), $name], $arguments);
    }
    
}
