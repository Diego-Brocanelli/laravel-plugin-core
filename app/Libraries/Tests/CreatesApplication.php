<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Tests;

use App\Plugin\Core\Libraries\Plugins\Handler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Str;
use App\Plugin\Core\Libraries\Composer\Parser;
use App\Plugin\Core\Providers\PluginServiceProvider;
use ReflectionClass;

trait CreatesApplication
{
    public function createApplication()
    {
        $pluginsHandler = Handler::instance();

        $className = $this->serviceProvider();
        $pluginsHandler->registerPlugin($className);

        // $class = new ReflectionClass($className);
        $plugin          = $pluginsHandler->plugin($className);
        $pluginNamespace = $plugin->config()->param('plugin_namespace');
        // $pluginTag       = Str::snake($pluginNamespace);
        $pluginPath      = $plugin->path();
        $laravelPath     = $plugin->config()->param('laravel_path');
        
        $config = (new Parser("{$pluginPath}/composer.json"))->all(true);

        $this->clearDiscovery($laravelPath, $config);

        $app = require "{$laravelPath}/bootstrap/app.php";

        // Muda a localização do diretório de ambiente.
        // Onde se encontra o .env
        $app->useEnvironmentPath($laravelPath);
        $app->useStoragePath($laravelPath . '/storage');

        $app->make(Kernel::class)->bootstrap();
        
        if (isset($config['extra']) 
         && isset($config['extra']['laravel'])
         && isset($config['extra']['laravel']['providers'])
        ) {
            
            // Disponibiliza os providers do módulo para o artisan
            foreach($config['extra']['laravel']['providers'] as $moduleProvider) {
                $app->register($moduleProvider);
            }
        }

        $this->restoreDiscovery($laravelPath);

        return $app;
    }

    // O package discovery do laravel contém todos os pacotes instalados
    // mas este mpdulo não possui essas dependencias.
    // por isso, todos os outros moduloes devem ser removidos do package discovery
    // para não interferirem nos testes de unidade
    private function clearDiscovery(string $laravelPath, array $composer)
    {
        $this->restoreDiscovery($laravelPath);

        $packagesFile = "{$laravelPath}/bootstrap/cache/packages.php";
        $packages = require $packagesFile;

        $cleanedPackages = [];
        foreach($packages as $packageName => $item) {
            
            if (strpos($item['providers'][0], 'App\\Plugin') === false
             && strpos($item['providers'][0], 'App\\Theme') === false
            ) {
                $cleanedPackages[$packageName] = $item;
                continue;
            }

            if ($composer['name'] === $packageName) {
                $cleanedPackages[$packageName] = $item;
            }
        }

        // backup
        shell_exec("mv {$packagesFile} {$packagesFile}.disabled;");
        
        $content = "<?php return ";
        $content .= $this->createPhpString($cleanedPackages);
        $content .= ";";

        file_put_contents($packagesFile, $content);
    }

    private function restoreDiscovery(string $laravelPath)
    {
        $packagesFile = "{$laravelPath}/bootstrap/cache/packages.php";

        if (is_file("{$packagesFile}.disabled") === true) {
            shell_exec("rm -f {$packagesFile}");
            shell_exec("mv {$packagesFile}.disabled {$packagesFile}");
        }
    }

    private function createPhpString(array $data, $spaces = 2)
    {
        $prefix = str_repeat(" ", $spaces);

        $content = "array (";

        foreach($data as $index => $item) {

            if (is_numeric($index)) {
                $content .= PHP_EOL . "{$prefix}{$index} => ";
            } else {
                $content .= PHP_EOL . "{$prefix}\"{$index}\" => ";
            }

            if (is_array($item)) {
                $content .= $this->createPhpString($item, 8) . ",";
            } else {
                $content .= " \"{$item}\"," . PHP_EOL;
            }
        }

        $content .= PHP_EOL . "{$prefix})";

        return $content;
    }
}
