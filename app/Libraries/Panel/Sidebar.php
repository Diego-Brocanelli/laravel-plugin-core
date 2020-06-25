<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Panel;

use Illuminate\Support\Str;

class Sidebar
{
    static $instance;
    
    private $entries = [];

    protected function __construct()
    {
        // Embora esta biblioteca possa ser acessada através do service container do Laravel
        // através do helper app('sidebar'), é preciso que ela seja sempre singleton, mesmo 
        // para acesso sem o service contanter. Por isso, o construtor deve ser recusado para 
        // o desenvolvedor e prover o singleton das duas formas!

        // Mais informações em app-module/Core/Providers/ServiceProvider.php
        // ex: $this->app->singleton('sidebar', function ($app) ...
    }

    public static function instance(): Sidebar
    {
        if (static::$instance === null) {
            static::$instance = new Sidebar();
        }

        return static::$instance;
    }

    public function entry(string $slug): ?Entry
    {
        $slug = Str::slug($slug);
        return $this->entries[$slug] ?? null;
    }

    public function append(Entry $item): Sidebar
    {
        $this->entries[$item->slug()] = $item;
        return $this;
    }

    public function prepend(Entry $item): Sidebar
    {
        $this->entries = array_merge([$item->slug() => $item], $this->entries);
        return $this;
    }

    public function remove(string $slug): Sidebar
    {
        if (isset($this->entries[$slug]) === true){
            unset($this->entries[$slug]);
        }

        return $this;
    }

    public function resetStatus(): Sidebar
    {
        array_walk_recursive(
            $this->entries, 
            fn(Entry $i) => $i->setStatus(Entry::STATUS_COMMON)
        );
        return $this;
    }

    public function allEntries(): array
    {
        return $this->entries;
    }

    public function toArray(): array
    {
        return array_map(fn($item) => $item->toArray(), $this->allEntries());
    }

    public function flush(): Sidebar
    {
        $this->entries = [];
        return $this;
    }
}
