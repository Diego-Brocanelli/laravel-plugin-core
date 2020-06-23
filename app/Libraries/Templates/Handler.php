<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Templates;

use InvalidArgumentException;

/**
 * Esta é a classe que permite acesso as todos os plugins registrados na aplicação.
 * Ela funciona como uma API, para que qualquer plugin seja acessível de forma direta.
 */
class Handler
{
    static $instance;
    
    private $views = [];

    private $reverseMap = [];

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
        $this->views = [];
        return $this;
    }

    /**
      * Registra a view que será usada no lugar do alvo encontrado no Módulo Core.
      * Qualquer view existente no Módulo Core pode ser usada como alvo de substituição.
      * Por exemplo, para usar um core::document personalizado, deve registrar o substituido
      * com: ...\Templates\Handler::instance()->registerView('core::body', 'meu::substituto.para.body')
      * 
      * @param string $targetView
      * @param string $replacementView
      */
    public function registerView(string $targetView, string $replacementView): Handler
    {
        if (view()->exists($targetView) === false) {
            throw new InvalidArgumentException(
                "A view '{$targetView}' correspondente ao protótipo '{$targetView}' não existe no Módulo Core");
        }
        
        if (view()->exists($replacementView) === false) {
            throw new InvalidArgumentException(
                "A view '{$replacementView}' não foi encontrada no sistema e por isso não foi registrada");
        }

        $this->views[$targetView] = $replacementView;

        // O mapa reverso serve para excluir uma view registrada
        // essa forma exige menos memória do que buscar dentro do array para excluir
        $this->reverseMap[$replacementView] = $targetView;

        return $this;
    }

    /**
      * Remove uma view substituta do registro de substituições
      * 
      * @param string $replacementView
      */
    public function removeView(string $replacementView): Handler
    {
        if (isset($this->reverseMap[$replacementView]) === false) {
            throw new InvalidArgumentException("A view de substituição {$replacementView} não existe no registro");
        }

        $targetView = $this->reverseMap[$replacementView];
        unset($this->views[$targetView]);
  
        return $this;
    }

    /**
      * Decide se a view possui substituta,
      * caso contrário, devolve a original
      * 
      * @param string $view
      */
    public function resolveExtendsCore(string $view): string
    {
        return $this->views[$view] ?? $view;
    }

    public function allReplaces(): array
    {
        return $this->views;
    }
}
