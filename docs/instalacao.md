# Instalação do pacote

Existem duas maneiras de instalar o pacote `bnw/laravel-plugin-core`:

## Instalando em um projeto Laravel 

Para essa modalidade, os recursos ficarão disponiveis diretamente na instalação normal do Laravel.

1. Instale o Laravel:

```bash
composer create-project laravel/laravel meu-projeto
cd meu-projeto
chmod -Rf 777 storage
chmod -Rf 777 bootstrap/cache
```

2. Instale o bnw/laravel-plugin-core

```bash
composer require bnw/laravel-plugin-core 
php artisan publish --tag="core-config"
php artisan publish --tag="core-assets"
php artisan publish --tag="core-theme"
```

3. Crie um ServiceProvider para o novo projeto

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

Troque a superclasse do seu ServiceProvider para que ela estenda  `App\Plugin\Core\Providers\PluggableServiceProvider`;

```php
<?php

namespace App\Providers;

use App\Plugin\Core\Providers\PluggableServiceProvider;

class MeuProjetoProvider extends PluggableServiceProvider
{
```

Para concluir, no arquivo de configuração `config/app.php`, adicione o seu novo provider na lista de providers:

```php
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    App\Providers\MeuProjetoProvider::class, // seu provider
],
```

Após isso, é possivel acessar o painel através da rota '/admin'.

O próximo passo é [personalizar o painel](painel.md), adicionando itens de menu, [páginas com regras de negócio](paginas.md) e tudo mais. 

## Instalando como um plugin isolado

Para essa modalidade, os recursos ficarão disponiveis dentro de um plugin isolado sob o contexto de uma pacote do composer.
O mecanismo utiliza o Laravel como dependência e possibilita o desenvolvimento modular.

É como se o desenvolvedor estivesse dentro do Laravel, mas na verdade está em um pacote totalmente separado.

Para mais informações sobre como criar um plugin, acesse [Criando um plugin isolado](plugin.md).

## Criando uma página de exemplo

> Essa parte está sendo documentada

## Mais informações

[Criando um plugin isolado](plugin.md)

[Voltar para o início](../readme.md)