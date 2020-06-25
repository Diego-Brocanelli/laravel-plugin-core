<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

use InvalidArgumentException;
use Illuminate\Support\Str;

class Plugin
{
    private $pluginName;

    private $pluginTag;
    
    private $pluginPath;

    private $pluginParams;
    
    private $pluginProviders = [];

    private $assetScriptsTop = [];

    private $assetScriptsBottom = [];

    private $assetStyles = [];

    private $assetTemplates = [];

    public function __construct(string $name, string $rootPath, array $providers = [])
    {
        $this->setName($name)
            ->setPath($rootPath)
            ->setConfig(new Config("{$rootPath}/config/"));

        array_walk($providers, fn($item) => $this->addProvider($item));

        $config = $this->config()->all(true);

        // podem existir vários arquivos de configuração
        // por isso é preciso varrer todos!
        foreach($config as $params) {

            if (isset($params['scripts'])) {
                array_walk($params['scripts'], fn($item) => $this->addScriptBottom($item));
            }

            if (isset($params['scripts_top'])) {
                array_walk($params['scripts_top'], fn($item) => $this->addScriptTop($item));
            }

            if (isset($params['styles'])) {
                array_walk($params['styles'], fn($item) => $this->addStyle($item));
            }

            if (isset($params['templates'])) {
                array_walk($params['templates'], fn($item, $target) => $this->addTemplateView($target, $item));
            }
        }
    }

    /**
      * O nome não pode ser mudado depois de um módulo ser construído.
      */
    protected function setName(string $name): Plugin
    {
        $this->pluginName = $name;
        return $this;
    }
    
    /**
      * Seta o caminho completo até o diretório root do módulo.
      * O caminho não pode ser mudado depois de um módulo ser construído.
      *
      * @param string $rootPath
      */
    protected function setPath(string $rootPath): Plugin
    {
        if (is_file("{$rootPath}/composer.json") === false) {
            throw new InvalidArgumentException("O caminho {$rootPath} do plugin {$this->pluginName} não parece conter um módulo válido");
        }

        $this->pluginPath = $rootPath;
        return $this;
    }
    
    /**
      * A instância de configuração não pode ser sobrescrita depois de um módulo ser construído. 
      * Parâmetros podem ser mudados usando a instância com self::config().
      */
    protected function setConfig(Config $config): Plugin
    {
        $this->pluginParams = $config;

        $namespace = $this->pluginParams->param('plugin_namespace');
        if ($namespace === null) {
            $namespace = $this->pluginParams->param('theme_namespace');
        }
        
        if ($namespace !== null) {
            $this->pluginTag = Str::snake($namespace);
            return $this;
        }
 
        $this->pluginTag = Str::snake($this->pluginName);
        return $this;
    }
    
    public function addProvider(string $serviceProviderClass): Plugin
    {
        // evita providers duplicados
        $this->pluginProviders[$serviceProviderClass] = $serviceProviderClass;
        return $this;
    }
    
    public function name(): string
    {
        return $this->pluginName;
    }
    
    public function tag(): string
    {
        return $this->pluginTag;
    }

    public function path(): string
    {
        return $this->pluginPath;
    }
    
    public function config() : Config
    {
        return $this->pluginParams;
    }

    public function providers(): array
    {
        return array_values($this->pluginProviders);
    }

    protected function formatPrefixUrl(): string
    {
        return "/plugins/" . $this->tag();
    }

    public function addScriptTop(string $scriptName): Plugin
    {
        // evita scripts duplicados
        $prefix = $this->formatPrefixUrl();
        $this->assetScriptsTop[$scriptName] = "{$prefix}/js/$scriptName";
        return $this;
    } 

    public function addScriptBottom(string $scriptName): Plugin
    {
        // evita scripts duplicados
        $prefix = $this->formatPrefixUrl();
        $this->assetScriptsBottom[$scriptName] = "{$prefix}/js/$scriptName";
        return $this;
    } 

    public function addStyle(string $styleName): Plugin
    {
        // evita estilos duplicados
        $prefix = $this->formatPrefixUrl();
        $this->assetStyles[$styleName] = "{$prefix}/css/$styleName";
        return $this;
    }

    // public function addTemplateView(string $target, string $view): Plugin
    // {
    //     // evita views duplicadas
    //     $this->assetTemplates[$target] = $view;
    //     return $this;
    // }

    public function scriptsTop(): array
    {
        return $this->assetScriptsTop;
    }

    public function scriptsBottom(): array
    {
        return $this->assetScriptsBottom;
    }

    public function scripts(): array
    {
        return array_merge($this->assetScriptsTop, $this->assetScriptsBottom);
    }

    public function styles(): array
    {
        return $this->assetStyles;
    }

    // public function templates(): array
    // {
    //     return $this->assetTemplates;
    // }
}
