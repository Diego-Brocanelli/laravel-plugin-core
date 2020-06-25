<?php

use App\Plugin\Core\Libraries\Plugins\Handler;

if (function_exists('dummy_core_helpers') === false) {

    // Funções não possuem escopo.
    // Por esse motivo, usa-se o artifício de verificar a existência da função 
    // da invocação do arquivo com helpers
    function dummy_core_helpers(){}

    function front_scripts_top()
    {
        return Handler::instance()->scriptsTop();
    }

    function front_scripts_bottom()
    {
        return Handler::instance()->scriptsBottom();
    }

    function front_styles()
    {
        return Handler::instance()->styles();
    }

    function vue($name)
    {
        $target = explode('::', $name);
        $plugin = Handler::instance()->plugin($target[0]);

        if ($target[1] === null) {
            throw new InvalidArgumentException("{$name} não é uma identificação válida para um componente vue. Use namespace::vuefile");
        }

        if ($plugin === null) {
            throw new InvalidArgumentException("O namespace {$target[0]} não foi registrado");
        }
        
        $vueFile = implode(DIRECTORY_SEPARATOR, [$plugin->path(), 'resources', 'js', 'pages', $target[1]]);

        // deve devolver as outras informações de estado setadas pelo controller

        return [
            'meta' => Handler::instance()->metadata(),
            'vuefile' => file_get_contents($vueFile . '.vue')
        ];
    }
}
