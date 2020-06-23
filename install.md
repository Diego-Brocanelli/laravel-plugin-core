# Procedimentos para criação de um plugin

## 1. Criar um projeto limpo do Laravel:

```
composer create-project laravel/laravel laravel-plugin-xxx
```

## 2. Corrigir os namespaces

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

## 3. Adicionar o projeto do laravel como dependência de desenvolvimento:

```
cd laravel-plugin-xxx
composer require --dev laravel/laravel
composer require laravel/ui
```

## 4. Adicionar o pacote Laravel UI:

```
composer require laravel/ui
```

## 5. Remover resíduos

Remover os seguintes arquivos:

```
rm -Rf app/Console
rm -Rf app/Exceptions
rm -f app/Http/Kernel.php
rm -Rf app/Http/Middleware
rm -f app/Providers/*.php
rm -f app/User.php
rm -Rf bootstrap
rm -f config/*.php
rm -f database/migrations/*.php
rm -f database/factories/*.php
rm -f tests/CreatesApplication.php
```

## 5. Criar o arquivo de configuração

```
touch config plugin_xxx.php
```

Adicionar o conteúdo:

```
<?php

declare(strict_types=1);

return [

    'plugin_namespace' => 'Xxx', 

    // Apenas para desenvolvimento do pacote! 
    // Este parâmetro notifica o Artisan sobre a localização 
    // da instalação principal do Laravel.
    'laravel_path' => realpath(__DIR__ . '/../../laravel'),
];
```

## 6. Modificar o arquivo 'app/Http/Controllers/Controller.php'

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
```

Depois:

```
use App\Plugin\Core\Http\Controllers\ModuleController;
class Controller extends ModuleController
```

## 6. Modificar o arquivo 'artisan'

Antes:

```
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

Depois:

```
require __DIR__.'/vendor/autoload.php';

$config = require_once __DIR__ . '/config/plugin_core.php';

class Kernel extends App\Console\Kernel
{
    protected function commands()
    {
        // Para adicionar comandos adicionais no artisan local
        $this->commands = [
            UiCommand::class
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

## 6. Criar um ServiceProvider

É preciso criar o service provider para atender á cnfiguração do composer.json do parâmetro "extra.laravel.providers".

No diretório app/Providers, crie o arquivo 'ServiceProvider' com o seguinte conteúdo:

```
<?php

declare(strict_types=1);

namespace App\Plugin\Xxx\Providers;

class ServiceProvider extends PluggableServiceProvider
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

## 7. Editar os scripts do composer

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

## 8. Editar o TestCase

Antes:

```
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
```

Depois:

```
declare(strict_types=1);

namespace Tests\Module\Core;

use App\Plugin\Core\Libraries\Tests\CreatesApplication;
use App\Plugin\Core\Libraries\Tests\ModuleTestCase;
use App\Plugin\Xxx\Providers\ServiceProvider;

abstract class TestCase extends ModuleTestCase
{
    protected function serviceProvider(): string
    {
        return ServiceProvider::class;
    }
}
```
