<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Composer;

use App\Plugin\Core\Libraries\Plugins\Config;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Foundation\Application;

class Scripts
{
    private static $instance;

    private $laravelPath;

    private $isBooted = false;

    private function __construct()
    {
        // acesso somente através do singleton
    }

    public static function instance(): Scripts
    {
        if (static::$instance === null) {
            static::$instance = new Scripts();
        }

        return static::$instance;
    }

    private function bootstrap($event): bool
    {
        // Autoload quando o pacote está em desenvolvimento
        $autoloadPackage = realpath(__DIR__ . '/../../../vendor/autoload.php');

        // Autoload quando o pacote está no vendor do projeto Laravel
        $autoloadVendor = realpath(__DIR__ . '/../../../../../../vendor/autoload.php');

        $autoload = $autoloadVendor !== false ? $autoloadVendor : $autoloadPackage;
        if ($autoload === false || is_file($autoload) === false) {
            return false;
        }

        require $autoload;

        $pluginConfig = getenv("TARGET_CONFIG");
        if ($pluginConfig === false) {
            $event->getIO()->error(
                'A variável de ambiente TARGET_CONFIG não foi encontrada no composer.json. ' .
                    'Adicione @putenv TARGET_CONFIG=meu_config_xxx na invocação do script "pre-autoload-dump"'
            );
            return false;
        }

        $this->laravelPath = self::instance()->config("{$pluginConfig}.laravel_path");

        if ($this->laravelPath === null) {
            $event->getIO()->error(
                "O arquivo de configuração {$pluginConfig} não contem o parâmetro 'laravel_path'. " .
                    "Este parâmetro deve indicar a localização real da instalação do Laravel"
            );
            return false;
        }

        if (is_file("{$this->laravelPath}/.env") === false) {
            $event->getIO()->error("O diretório {$this->laravelPath} não parece conter uma instalação válida do Laravel");
            return false;
        }

        $this->isBooted = true;
        return true;
    }

    protected function booted()
    {
        return $this->isBooted;
    }

    /**
     * Obtem o valor de um parâmetro de configuração existente neste módulo.
     * A busca deve ser feira usando a notação pontuada do Laravel.
     * Ex.: plugin_core.minha_conf.legal
     */
    private function config(string $param)
    {
        $configPath = getcwd() . '/config';

        $pluginConfig = getenv("TARGET_CONFIG");
        if (is_file($configPath . "/{$pluginConfig}.php") === false) {
            throw new \Exception("O arquivo {$configPath}/{$pluginConfig}.php não foi encontrado");
        }

        return (new Config($configPath))->param($param);
    }

    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        $script = self::instance();
        if ($script->bootstrap($event) === false) {
            return;
        }
        $script->clearCompiled();
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        $script = self::instance();
        if ($script->bootstrap($event) === false) {
            return;
        }
        $script->clearCompiled();
    }

    /**
     * Manipula o evento de 'pre-autoload-dump'.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     * @see https://getcomposer.org/apidoc/master/Composer/Script/Event.html
     */
    public static function preAutoloadDump($event)
    {
        $script = self::instance();
        if ($script->bootstrap($event) === false) {
            return;
        }
        $script->updatePlugin($event);
        $script->clearCompiled();
        $script->clearCache();
    }

    /**
     * Atualiza o módulo automaticamente na instalação do Laravel.
     * Para não precisar executar "composer update".
     *
     * @return void
     */
    protected function updatePlugin($event)
    {
        $laravel = new Application($this->laravelPath);

        $composerJson = getcwd() . "/composer.json";
        if (is_file($composerJson) === false) {
            $event->getIO()->error("O arquivo {$composerJson} não foi encontrado");
        }

        $config = @json_decode(file_get_contents($composerJson));
        if (json_last_error() !== JSON_ERROR_NONE) {
            $event->getIO()->error("O arquivo {$composerJson} é inválido, ou está corrompido");
        }

        $laravelVendor = $laravel->basePath("vendor");
        if (is_dir($laravelVendor) === false) {
            $event->getIO()->error("O diretório {$laravelVendor} não foi encontrado");
        }

        $this->updateVersionFile();

        $develPath = getcwd();
        $installedPath = $laravel->basePath("vendor/{$config->name}");
        echo shell_exec("rm -Rf {$installedPath}");
        $this->copyDirectory($develPath, $installedPath);

        // Publica os assets
        $pluginConfig = getenv("TARGET_CONFIG");
        $tag = 'assets-' . str_replace(['plugin_', 'theme_'], '', $pluginConfig);
        $event->getIO()->write("> Publicando assets na tag {$tag}");
        echo shell_exec("cd {$this->laravelPath}; php artisan vendor:publish --tag={$tag} --force");
        echo shell_exec("cd {$this->laravelPath}; php artisan vendor:publish --tag=assets-core-theme --force");
    }

    public function updateVersionFile(): void
    {
        $versionFile = getcwd() . DIRECTORY_SEPARATOR . 'version';
        if (is_file($versionFile) === false) {
            return;
        }

        $version = explode('.', shell_exec("git describe --abbrev=0"));
        $version[2] = (int)$version[2] + 1; 
        $version = implode('.', $version);
        file_put_contents($versionFile, $version);
    }
    

    /**
     * Limpa os arquivos de cachê do Laravel.
     *
     * @return void
     */
    public function clearCompiled()
    {
        $laravel = new Application($this->laravelPath);

        if (file_exists($servicesPath = $laravel->getCachedServicesPath())) {
            @unlink($servicesPath);
        }

        if (file_exists($packagesPath = $laravel->getCachedPackagesPath())) {
            @unlink($packagesPath);
        }
    }

    public function clearCache()
    {
        shell_exec(implode(";", [
            "cd {$this->laravelPath}",
            "php artisan view:clear",
            "php artisan optimize:clear",
            "php artisan package:discover --ansi"
        ]));
    }

    private function copyDirectory(string $source, string $destination): void
    {
        $dir = opendir($source);
        @mkdir($destination);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    $this->copyDirectory($source . '/' . $file, $destination . '/' . $file);
                } else {
                    copy($source . '/' . $file, $destination . '/' . $file);
                }
            }
        }

        closedir($dir);
    }
}
