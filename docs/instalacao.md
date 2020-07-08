# Instalação do pacote

Existem duas maneiras de instalar o pacote `bnw/laravel-plugin-core`:

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
php artisan publish --tag="core-config"
php artisan publish --tag="core-assets"
php artisan publish --tag="core-theme"
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

Troque a superclasse do seu ServiceProvider para que ela estenda  `App\Plugin\Core\Providers\PluggableServiceProvider`;

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

    App\Providers\MeuProjetoProvider::class, // seu provider
],
```

Após isso, é possivel acessar o painel através da rota '/admin'.

O próximo passo é [personalizar o painel](painel.md), adicionando itens de menu, [páginas com regras de negócio](paginas.md) e tudo mais. 

## Instalando como um plugin isolado

Para essa modalidade, os recursos ficarão disponiveis dentro de um plugin isolado sob o contexto de uma pacote do composer.
O mecanismo utiliza o Laravel como dependência e possibilita o desenvolvimento modular.

É como se o desenvolvedor estivesse dentro do Laravel, mas na verdade está em um pacote totalmente separado.

### 1. Crie um diretório para os pacotes isolados

Para esse exemplo usaremos 'plugins', mas você pode usar qualquer nome:

```bash
mkdir plugins
```

### 2. Crie um plugin baseado no esqueleto de exemplo

O [pacote de exemplo](https://github.com/bueno-networks/laravel-plugin-example) é um bom ponto de partida para novos plugins:

```bash
cd plugins
composer create-project bnw/laravel-plugin-example meu-plugin
cd meu-plugin
```

### 3. Renomeie os namespaces do esqueleto de exemplo

O pacote de exemplo vem com o nome 'Example'. Isso deve ser mudado pelo
nome do seu plugin. Para esse exemplo vamos usar o nome 'MeuPlugin':

### 3.1. composer.json

No arquivo do composer, atualize as seguintes informações:

O parâmetro "name" precisa conter o nome do pacote do seu plugin. 
Você pode escolher qualquer um, desde que esteja no formado do composer `seu-vendor/nome-do-seu-pacote`.

Para esse exemplo, vamos usar `bnw/meu-plugin`:

```javascript
{
    "name": "bnw/meu-plugin",
    "description": "Meu plugin será bem legal",
    ...
```

Será preciso também apontar o ServiceProvider correto no parâmetro **laravel.providers**. Neste exemplo usaremos `App\Plugin\MeuPlugin` como namespace:

```javascript
"extra": {
        "laravel": {
            "providers": [
                "App\\Plugin\\MeuPlugin\\Providers\\ServiceProvider"
            ]
        }
    },
```

Da mesma maneira, o namespace do autoload deverá ser atualizado para `App\Plugin\MeuPlugin`:

```javascript
"autoload": {
        "psr-4": {
            "App\\Plugin\\MeuPlugin\\": "app/"
        },
```

Por fim, no script `pre-autoload-dump`, será necessário setar o nome do arquivo de configuração de seu plugin. Usaremos `TARGET_CONFIG=plugin_meu_plugin`:

```javascript
"scripts": {
        "pre-autoload-dump": [
            "@putenv TARGET_CONFIG=plugin_meu_plugin",
            "App\\Plugin\\Core\\Libraries\\Composer\\Scripts::preAutoloadDump"
        ],
```

Mais informações sobre a utilidade deste script pode ser lida em [Desenvolvendo um plugin de forma isolada](plugin.md).

### 3.2. Arquivo de configuração

No pacote de exemplo, o arquivo de configuração se chama `config/plugin_example.php`, 
vamos mudar isso:

```shell
mv config/plugin_example.php config/plugin_meu_plugin.php
```

> OBS: o nome do arquivo de configuração é procurado pelo mecanismo de plugins sob o formato de 'plugin_?????', transformando palavras compostas como "MeuPlugin" para um nome correspondente no formato snake_case. Assim, `MeuPlugin` se tornará `meu_plugin`, onde o nome do arquivo de configuração deverá ser `plugin_meu_plugin.php`.

Dentro do arquivo de configuração, mude o nome do pacote adequadamente.

```php
<?php

declare(strict_types=1);

return [

    'plugin_name' => 'MeuPlugin',
```

> Obs: aqui é necessário apenas o nome do pacote, ou seja, `MeuPlugin` pois o mecanismo de plugins irá adicionar automaticamente o prefixo `App\Plugin`:

Não se importe com o parâmetro `laravel_path` agora, pois vamos falar dele mais tarde em [Desenvolvendo um plugin de forma isolada](plugin.md).

### 3.3. O controlador abstrato

No diretório `app/Http/Controllers` existe um controlador abstrato chamado `AController`. Mude o namespace dele para o do seu plugin:

```php
<?php

namespace App\Plugin\MeuPlugin\Http\Controllers;

use App\Plugin\Core\Http\Controllers\PluggableController as BaseController;

abstract class AController extends BaseController
{
    // ... 
}
```

No mesmo diretório existem outros dois controladores de exemplo. Caso você prefira usá-los, altere o namespace deles também. Caso contrário exclua-os!

### 3.4. O ServiceProvider

Obviamente, o namespace do ServiceProvider precisa ser atualizado:

```php 
<?php

declare(strict_types=1);

namespace App\Plugin\MeuPlugin\Providers;
```

Neste ServiceProvider existem várias definições de exemplo, implementadas no método boot(), remova-as deixando o método limpo como a seguir: 

```php 
public function boot()
{
    parent::boot();
}
```

> OBS 1: mais informações sobre essas definições são explicadas na [API PHP](api-php.md).

> OBS 2: o código acima é apenas para demonstrar que é necessário invocar o parent::boot() caso seja necessário adicionar implementações no método boot(). Caso não precise implementar nada, você pode remover o método boot do ServiceProvider.

> OBS 3: note que o ServiceProvider estende `App\Plugin\Core\Providers\PluggableServiceProvider`. Isso porque ele possui vários recursos adicionais para gerenciemnto de plugins.

### 3.5. Arquivos de exemplo que podem ser removidos

Os seguintes arquivos são apenas para exemplo, e podem ser removidos caso não tenha a intenção de usá-los:

- app/Http/Controllers/ApiController.php
- app/Http/Controllers/FrontController.php
- app/Models/Test.php
- database/factories/TestFactory.php
- resources/vue/example/*

### 3.6. Arquivos de assets

Caso seu plugin implementar assets, altere adequadamente os nomes nos seguintes arquivos:

- webpack.mix.js
- resources/js/example.js
- resources/sass/example.scss

### 3.7. database/seeds/DatabaseSeeder.php

Altere o namespace do mapeador de seeds:

```php 
<?php

namespace App\Plugin\MeuPlugin\Database;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
```

### 3.8. Arquivos de exemplo que podem ser removidos

Por fim, edite os testes adequadamente. 

- tests/Unit/ExampleTest.php
- tests/Feature/ExampleTest.php

### 4. Configurar o Laravel para usar o seu plugin

Agora que o plugin está ajustado, é preciso fazer uma instalação independente do Laravel:

Suba um diretório acima, de forma que você fique no diretório
`plugins`, criado no passo 1:

```bash
cd ../
pwd 
/home/fulano/plugins # faz de conta :)
ls -la
drwxrwxr-x  3 ricardo ricardo 4096 jul  8 09:57 .
drwxrwxr-x  5 ricardo ricardo 4096 jul  8 09:53 ..
drwxrwxr-x 10 ricardo ricardo 4096 jul  8 09:57 meu-plugin
```

Ao lado do seu plugin, faça uma instalação limpa do Laravel:

```bash
composer create-project laravel/laravel meu-laravel
cd meu-laravel
chmod -Rf 777 storage
chmod -Rf 777 bootstrap/cache
```

Após a instalação terminar, edite o arquivo `composer.json` do Laravel, adicionando a localização do seu plugin como um repositório do tipo `path`:

```javascript
"repositories": [
    {
        "type": "path",
        "url": "../meu-plugin",
        "options": {
            "symlink": false
        }
    }
],
```

Na linha de comando, instale o seu plugin:

```bash
composer require bnw/meu-plugin
```

A saída do comando será parecida com a seguir:

```bash
Package operations: 3 installs, 0 updates, 0 removals
- Installing laravel/ui (v2.1.0)
- Installing bnw/laravel-plugin-core (dev-master b2c2b76)
- Installing bnw/meu-plugin (dev-master): Mirroring from ../meu-plugin
```

Após a instalação, acesse seu plugin e execute o comando dumpautoload do composer para sincronizar seu plugin com a nova instalação do Laravel:

 ```bash
 cd ../meu-plugin
composer dumpautoload
```

Se tudo der certo, você terá uma mensagem parecida com essa:

```bash
Generating optimized autoload files
> @putenv TARGET_CONFIG=plugin_meu_plugin
> App\Plugin\Core\Libraries\Composer\Scripts::preAutoloadDump
> Publicando assets na tag meu-plugin-assets
```

## Mais informações

[Desenvolvendo um plugin de forma isolada](plugin.md)

[Voltar para o início](../readme.md)