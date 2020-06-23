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

}
