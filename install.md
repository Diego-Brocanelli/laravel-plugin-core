# Procedimentos para criação de um plugin

## 1. Criar um projeto limpo do Laravel:

```
composer create-project laravel/laravel laravel-plugin-xxx
```

## 2. Corrigir os namespaces

Por padrão, um projeto laravel é desenvolvido sob o namespace '\App'.
Como o plugin final será usado como um pacote, ele deve ter seu próprio
namespace para que o composer adicione-o corretamente como uma 
dependência.

### database/seeds/DatabaseSeeder.php

```
namespace App\Plugin\Xxx\Database; <- adicionado
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
```

### composer.json

Parâmetro "name" antes:

```
"laravel/laravel"
```

Parâmetro "name" depois:

```
"bnw/laravel-plugin-xxx"
```

Parâmetro "extra.laravel" antes:

```
{ 
    "dont-discover": [] 
}
```

Parâmetro "extra.laravel" depois:

``` 
{
    "providers": [ 
        "App\\Plugin\\Xxx\\Providers\\ServiceProvider" 
    ]
}
```

Parâmetro "autoload.psr4" antes:

```
"App\\": "app/"
```

Parâmetro "autoload.psr4" depois:

```
 "App\\Plugin\\Xxx\\": "app/"
```

## 3. Adicionar dependências de desenvolvimento:

O plugin precisa dos recursos do Laravel contidos no projeto 
original, por eese motivo, o projeto deverá ser usado na forma 
de dependencia apenas durante o densenvolvimento.

O pacote UI do Laravel provê facilidades para a criação de 
componentes do vuejs, por isso também é um excelente aliado
na criação de plugins.

```
cd laravel-plugin-xxx
composer require --dev laravel/laravel laravel/ui
```

## 4. Adicionar dependências de projeto:

Todo o mecanismo de gerenciamento de plugins está no pacote 
bnw/laravel-plugin-core, que deve ser usado em todos os plugins 
que venham a ser desenvolvidos para o Laravel.

```
composer require bnw/laravel-plugin-core
```

## 5. Remover resíduos

Remover os seguintes arquivos:

```
rm -Rf app/Console
rm -Rf app/Exceptions
rm -Rf app/Http/Middleware
rm -f app/Http/Kernel.php
rm -f app/Providers/*.php
rm -f app/User.php
rm -Rf bootstrap
rm -f config/*.php
rm -f database/migrations/*.php
rm -f database/factories/*.php
rm -f tests/CreatesApplication.php
```

## 6. Criar o arquivo de configuração

```
touch config/plugin_xxx.php
```

Adicionar o conteúdo:

```
<?php

declare(strict_types=1);

return [

    'plugin_namespace' => 'Xxx', 

    // Apenas para desenvolvimento do pacote! 
    // Este parâmetro notifica o Artisan sobre a localização 
    // da instalação principal do Laravel a fim de facilitar
    // a atualização do pacote na instalação ofocial.
    'laravel_path' => realpath(__DIR__ . '/../../laravel'),
];
```

## 7. Modificar o arquivo 'app/Http/Controllers/Controller.php'

Mudar o namespace:

Antes:

```
namespace App\Http\Controllers;
```

Depois:

```
namespace App\Plugin\Xxx\Http\Controllers;
```

Mudar a superclasse:

Antes:

```
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
```

Depois:

```
use App\Plugin\Core\Http\Controllers\PluggableController as BaseController;

class Controller extends BaseController
{
    // ... <-- removidos os traits
}
```

## 8. Modificar o arquivo 'artisan'

Antes:

```
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

Depois:
> Obs: não esqueça de mudar os namespaces "xxx" para o correto do seu plugin.

```
require __DIR__.'/vendor/autoload.php';

$config = require_once __DIR__ . '/config/plugin_xxx.php';

class Kernel extends App\Console\Kernel
{
    protected function commands()
    {
        // Para adicionar comandos adicionais no artisan local
        $this->commands = [
            \Laravel\Ui\UiCommand::class
        ];
        return parent::commands();
    }
}

class Application extends Illuminate\Foundation\Application
{
    public function useModuleNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function resourcePath($path = '')
    {
        return __DIR__ . DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

$app = new \Application(
    $_ENV['APP_BASE_PATH'] ?? __DIR__.'/vendor/laravel/laravel'
);
$app->useModuleNamespace("App\\Plugin\\{$config['plugin_namespace']}\\");
$app->useAppPath(__DIR__ . '/app');
$app->useDatabasePath(__DIR__ . '/database');
$app->useStoragePath($config['laravel_path'] . '/storage');
$app->useEnvironmentPath($config['laravel_path']);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    \Kernel::class 
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);
```

## 9. Criar um ServiceProvider

É preciso criar o service provider para atender á cnfiguração do composer.json do parâmetro "extra.laravel.providers".

No diretório app/Providers, crie o arquivo 'ServiceProvider':

```
touch app/Providers/ServiceProvider.php
```

### a) Se for um plugin

Adicione o seguinte conteúdo:

```
<?php

declare(strict_types=1);

namespace App\Plugin\Xxx\Providers;

use App\Plugin\Core\Providers\PluginServiceProvider;

class ServiceProvider extends PluginServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }
}
```

### a) Se for um tema

Adicione o seguinte conteúdo:

```
<?php

declare(strict_types=1);

namespace App\Plugin\Xxx\Providers;

use App\Plugin\Core\Providers\ThemeServiceProvider;

class ServiceProvider extends ThemeServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }
}
```

## 10. Atualizar o TestCase

Antes:

```
<?php 

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
```

Depois:

```
<?php

declare(strict_types=1);

namespace Tests;

use App\Plugin\Core\Libraries\Tests\PluginTestCase;
use App\Plugin\Xxx\Providers\ServiceProvider;

abstract class TestCase extends PluginTestCase
{
    protected function serviceProvider(): string
    {
        return ServiceProvider::class;
    }
}
```

## 11. Atualizar o autoloader do composer

```
composer dumpautoload
```

## 12. Editar os scripts do composer

Para facilitar o desenvolvimeto de plugins, deve-se 
adicionar scripts especiais ao composer.

Em especial, o "composer watch" é usado para sincronizar o pacote sempre que uma alteração for efetuada em desenvolvimento. É muito útil para não 
precisar ficar dando "composer update" e aguardar o pacote ser atualizado todas as vezes.

> Obs: para o composer watch funcionar, é preciso instalar o software `inotifywait`. Se estiver em sistemas debian, use `sudo apt install inotify-tools`.

Antes:

```
"scripts": {
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ],
    "post-root-package-install": [
        "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
    ],
    "post-create-project-cmd": [
        "@php artisan key:generate --ansi"
    ]
}
```

Depois:
> Obs: não esqueça de trocar o namespace 'xxx' pelo do seu plugin.

```
"scripts": {
    "pre-autoload-dump": [
        "@putenv TARGET_CONFIG=plugin_xxx",
        "App\\Plugin\\Core\\Libraries\\Composer\\Scripts::preAutoloadDump"
    ],
    "test": [
        "composer dumpautoload --ansi; vendor/bin/phpunit"
    ],
    "watch": [
        "while inotifywait --exclude='.git' -e 'modify' -e 'create' -e 'delete' -r -q ./; do composer dumpautoload; done"
    ]
}
```

Parâmetro "config" antes:

```
"config": {
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true
},
```

Parâmetro "config" depois:

```
"config": {
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true,
    "process-timeout":0 <- adicionado
},
```

## 13. Preparando o ambiente de desenvolvimento

O objetivo é simular a execução do pacote em uma instalação real do Laravel. Por isso, é preciso que, ao lado do diretório da instalação deste pacote, exista uma instalação limpa do Laravel.

O diretório com o ambiente de desenvolvimento deverá estar assim:

```
ls meu-diretorio-dev
laravel laravel-plugin-xxx laravel-plugin-yyy laravel-plugin-zzz
```

Para que o Laravel encontre os plugins, é preciso adicioná-los no composer da instalação limpa do Laravel:

### a) Instale o laravel-plugin-core

O mecanismo de plugins deve estar presente na instalação do Laravel:

```
composer require bnw/laravel-plugin-core
```

### b) Adicione um repositório local

Cada plugin deverá possuir uma entrada como a mostrada abaixo:

```
"repositories": [
    {
        "type": "path",
        "url": "../laravel-plugin-xxx",
        "options": {
            "symlink": false
        }
    }
    
],
```

### c) Instale o plugin

Importante:
Por se tratar de um plugin local, não é possível usar o comando `composer install`. Portanto, após o Laravel ser instalado, use:

```
composer require bnw/laravel-plugin-xxx
```

Pronto, agora é possivel trabalhar num ambiente simulado.

## Dicas

Uma vez dentro do diretório do plugin, os comandos do composer ajudarão no processo, dispensando a necessidade de atualizar a instalação limpa do Laravel.

Comandos:

### $ composer watch

Semelhante ao `npm run watch`, este comando fica vigiando por alterações no código fonte do plugin. Qualquer alteração é sincronizada com a instalação do Laravel.

### $ composer dumpautoload

No plugin, quando o `composer dumpautoload` é executado, todas as 
alterações efetuadas também serão sincronizadas com a instalação do Laravel.

### $ composer test

Executa os testes de unidade dentro do pacote.
