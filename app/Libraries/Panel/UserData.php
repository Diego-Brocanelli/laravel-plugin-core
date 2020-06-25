<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Panel;

class UserData
{
    static $instance;
    
    private $data = [];

    protected function __construct()
    {
        // Embora esta biblioteca possa ser acessada através do service container do Laravel
        // através do helper app('sidebar'), é preciso que ela seja sempre singleton, mesmo 
        // para acesso sem o service contanter. Por isso, o construtor deve ser recusado para 
        // o desenvolvedor e prover o singleton das duas formas!

        // Mais informações em app-module/Core/Providers/ServiceProvider.php
        // ex: $this->app->singleton('sidebar', function ($app) ...
    }

    public static function instance(): UserData
    {
        if (static::$instance === null) {
            static::$instance = new UserData();
        }

        return static::$instance;
    }

    public function setName(string $name): UserData
    {
        $this->data['name'] = $name;
        return $this;
    }

    public function name(): string
    {
        return $this->data['name'];
    }

    public function setLogin(string $login): UserData
    {
        $this->data['login'] = $login;
        return $this;
    }

    public function login(): string
    {
        return $this->data['login'];
    }

    public function setPicture(string $imgsrc): UserData
    {
        $this->data['picture'] = $imgsrc;
        return $this;
    }

    public function picture(): string
    {
        return $this->data['picture'];
    }

    public function setPermissions(array $list): UserData
    {
        $this->data['permissions'] = $list;
        return $this;
    }

    public function permissions(): string
    {
        return $this->data['permissions'];
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function flush(): UserData
    {
        $this->data = [];
        return $this;
    }
}
