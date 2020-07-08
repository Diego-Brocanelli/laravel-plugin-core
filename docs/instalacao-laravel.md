# Instalação do pacote em um projeto Laravel

Existem duas maneiras de instalar o pacote `bnw/laravel-plugin-core`:

- Em um projeto Laravel
- [Em um plugin isolado ](instalacao-plugin.md)

## Instalando em um projeto Laravel 

Para essa modalidade, os recursos ficarão disponiveis diretamente na instalação normal do Laravel.

### 1. Instale o Laravel:

```bash
composer create-project laravel/laravel meu-projeto
cd meu-projeto
chmod -Rf 777 storage
chmod -Rf 777 bootstrap/cache
```

### 2. Instale o bnw/laravel-plugin-core

```bash
composer require bnw/laravel-plugin-core 
php artisan vendor:publish --tag="core-config"
php artisan vendor:publish --tag="core-assets"
php artisan vendor:publish --tag="core-theme"
```

### 3. Crie um ServiceProvider para o novo projeto

```bash
php artisan make:provider MeuProjetoProvider
```

Por padrão, os providers estendem `illuminate\Support\ServiceProvider`.

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MeuProjetoProvider extends ServiceProvider
{
```

Troque a superclasse do seu ServiceProvider para que ele estenda  `App\Plugin\Core\Providers\PluggableServiceProvider`;

```php
<?php

namespace App\Providers;

use App\Plugin\Core\Providers\PluggableServiceProvider;

class MeuProjetoProvider extends PluggableServiceProvider
{
```

### 4. Instale o seu ServiceProvider no Laravel

Para concluir, no arquivo de configuração `config/app.php`, adicione o seu novo provider na lista de providers:

```php
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    App\Providers\MeuProjetoProvider::class, // seu provider aqui
],
```

Após isso, é possivel acessar o painel através da rota '/admin'.

> OBS: para a rota `/admin` estar acessível pelo navegador é preciso que o projeto Laravel seja executado por algum servidor como NGINX, ou conteinerizado com Docker.

O próximo passo é [personalizar o painel](painel.md), adicionando itens de menu, [páginas com regras de negócio](paginas.md) e tudo mais. 

## Mais informações

### Usando em um projeto Laravel
- [Instalando em um projeto Laravel](instalacao-laravel.md)
- [Criando páginas no painel](paginas.md)
- [Manipulando o painel](painel.md)
- [API Javascript](api-js.md)
- [API PHP](api-php.md)

### Usando em um plugin isolado
- [Instalando em um plugin isolado](instalacao-plugin.md)
- [Implementando um plugin](plugin.md)

[Voltar para o início](../readme.md)
