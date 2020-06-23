<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Esta é a classe que permite acesso às configurações do módulo antes do Laravel ser carregado.
 * Não há necessidade de utilizá-la dentro das implementaçções normais do Laravel,
 * destina-se somente aos scripts executados entes do carregamento do framework.
 */
class Config
{
    private $configPath;

    private $moduleConfigFirst;
    
    private $moduleConfig;

    private $moduleConfigRaw;

    public function __construct(string $configPath)
    {
        if (is_dir($configPath) === false) {
            throw new InvalidArgumentException("O caminho {$configPath} para os arquivos de configuração é inválido");
        }
        
        $this->configPath = $configPath;
    }

    /**
      * Obtem todos os parâmetros de configuração registrados neste módulo.
      * 
      * @return array
      */
    public function all(bool $raw = false) : array
    {
        if ($this->moduleConfig === null) {

            $path = $this->configPath;  
            
            $list = scandir($path);
            array_map(function($file) use($path) {
            
                if (in_array($file, ['.', '..']) === true) {
                    return $path;
                }
                
                $configName = str_replace(['-', '.php'], ['_',''], $file);
                $this->moduleConfig[$configName] = include \realpath("{$path}/{$file}");
                
                if ($this->moduleConfigFirst === null) {
                    $this->moduleConfigFirst = $configName;
                }
                
            }, $list);

            $this->moduleConfigRaw = $this->moduleConfig;
            $this->moduleConfig    = Arr::dot($this->moduleConfig);
        }

        if ($raw === true) {
            return $this->moduleConfigRaw ?? [];
        }

        return $this->moduleConfig ?? [];
    }

    /**
      * Obtem o valor de um parâmetro de configuração existente neste módulo.
      * A busca deve ser feita usando a notação pontuada do Laravel.
      * Ex.: module_core.minha_conf.legal
      */
    public function param(?string $name)
    {
        // para carregar os parâmetros, caso não existam ainda
        $this->all();

        if ($this->moduleConfig === null) {
            return null;
        }
        
        if (isset($this->moduleConfig[$name]) === true) {
            return $this->moduleConfig[$name];
        }

        return $this->moduleConfig["{$this->moduleConfigFirst}.{$name}"] ?? null;
    }
    
    public function flush(): Config
    {
        $this->moduleConfig = null;
        return $this;
    }
}
