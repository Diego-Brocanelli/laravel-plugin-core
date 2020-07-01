<?php

use App\Plugin\Core\Libraries\Plugins\Handler;

if (function_exists('dummy_core_helpers') === false) {

    // Funções não possuem escopo.
    // Por esse motivo, usa-se o artifício de verificar a existência da função 
    // da invocação do arquivo com helpers
    function dummy_core_helpers()
    {
    }

    /**
     * Invoca um arquivo do vue para a respostas a ser compilada pelo vuejs.
     * Os arquivos do vue devem estar em resources/js/pages. 
     * Por exemplo: a tag 'core::example' irá invocar o arquivo 'resources/js/pages/example.vue'
     * 
     * @param string $name
     */
    function vue(string $name): array
    {
        $target = explode('::', $name);
        $plugin = Handler::instance()->plugin($target[0]);

        if (isset($target[1]) === false) {
            throw new InvalidArgumentException("{$name} não é uma identificação válida para um componente vue. Use namespace::vuefile");
        }

        if ($plugin === null) {
            throw new InvalidArgumentException("O namespace {$target[0]} não foi registrado");
        }

        $filePath = str_replace('.', '/', $target[1]);
        $vueFile = implode(DIRECTORY_SEPARATOR, [$plugin->path(), 'resources', 'vue', $filePath]);

        if (is_file($vueFile . '.vue') === false) {
            throw new InvalidArgumentException("A arquivo {$vueFile}.vue não foi encontrado");
        }

        return [
            'meta' => Handler::instance()->metadata(),
            'vuefile' => file_get_contents($vueFile . '.vue')
        ];
    }
}
