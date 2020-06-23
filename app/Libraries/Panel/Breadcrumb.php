<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Panel;

class Breadcrumb
{
    static $instance;
    
    private $entries = [];

    private function __construct()
    {
        // Embora esta biblioteca possa ser acessada através do service container do Laravel
        // através do helper app('breadcrumb'), é preciso que ela seja sempre singleton, mesmo 
        // para acesso sem o service contanter. Por isso, o construtor deve ser recusado para 
        // o desenvolvedor e prover o singleton das duas formas!

        // Mais informações em app-module/Core/Providers/ServiceProvider.php
        // ex: $this->app->singleton('breadcrumb', function ($app) ...
    }

    public static function instance(): Breadcrumb
    {
        if (static::$instance === null) {
            static::$instance = new Breadcrumb();
        }

        return static::$instance;
    }

    public function entry(string $slug): ?Entry
    {
        return $this->entries[$slug] ?? null;
    }

    public function append(Entry $item): Breadcrumb
    {
        $this->entries[$item->slug()] = $item;
        return $this;
    }

    public function remove(string $slug): Breadcrumb
    {
        if (isset($this->entries[$slug]) === true){
            unset($this->entries[$slug]);
        }

        return $this;
    }

    public function allEntries(): array
    {
        return $this->entries;
    }

    public function flush(): Breadcrumb
    {
        $this->entries = [];
        return $this;
    }
}
