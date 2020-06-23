<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Plugins\Handler;
use App\Plugin\Core\Libraries\Plugins\Plugin;
use App\Plugin\Core\Libraries\Plugins\Theme;
use App\Plugin\Core\Libraries\Plugins\ThemeCore;
use Error;
use Exception;
use InvalidArgumentException;
use \PHPUnit\Framework\TestCase;
use Tests\files\Fake\ModuleOne\app\One\Providers\ServiceProviderInvalid;
use Tests\files\Fake\ModuleOne\app\One\Providers\ServiceProviderOne;
use Tests\files\Fake\ModuleTwo\app\Two\Providers\ServiceProviderTwo;

// Atenção!
// Os testes devem extender 'Tests\Module\TestCase'!!
// Este é um caso a parte para testar o mecanismo principal 

class CoreLibrariesPluginsHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        // Zera o manipulador para não haver interferência nos testes
        Handler::instance()->flush();
    }

    /** @test */
    public function singleton()
    {
        $this->expectException(Error::class);
        new Handler();
    }

    // /** @test */
    // public function registerNotExists()
    // {
    //     $this->expectException(InvalidArgumentException::class);
    //     $this->expectExceptionMessage("O ServiceProvider ".ServiceProviderOne::class."Invalid não existe");
    //     $instance = Handler::instance();
    //     $instance->registerPlugin(ServiceProviderOne::class . 'Invalid');
    // }

    // /** @test */
    // public function registerInvalid()
    // {
    //     $this->expectException(InvalidArgumentException::class);
    //     $this->expectExceptionMessage("O ServiceProvider ".ServiceProviderInvalid::class." é inválido, pois não implementa Illuminate\Support\ServiceProvider");
    //     $instance = Handler::instance();
    //     $instance->registerPlugin(ServiceProviderInvalid::class);
    // }

    // /** @test */
    // public function registerPlugin()
    // {
    //     $instance = Handler::instance();

    //     $instance->registerPlugin(ServiceProviderOne::class);
    //     $last = $instance->lastPlugin();
    //     $this->assertEquals('one', $last->tag());

    //     $instance->registerPlugin(ServiceProviderTwo::class);
    //     $last = $instance->lastPlugin();
    //     $this->assertEquals('two', $last->tag());

    //     $this->assertCount(2, $instance->allPlugins());

    //     // Não pode haver duplicidade de módulos
    //     $instance->registerPlugin(ServiceProviderOne::class);
    //     $last = $instance->lastPlugin();
    //     $this->assertEquals('two', $last->tag());

    //     $this->assertCount(2, $instance->allPlugins());

    //     // Obtém o módulo pelo provider
    //     $plugin = $instance->plugin(ServiceProviderTwo::class);
    //     $this->assertInstanceOf(Plugin::class, $plugin);
    //     $this->assertEquals('Two', $plugin->name());
    //     $this->assertEquals('two', $plugin->tag());

    //     // Obtém o módulo pela tag
    //     $plugin = $instance->plugin('one');
    //     $this->assertInstanceOf(Plugin::class, $plugin);
    //     $this->assertEquals('One', $plugin->name());
    //     $this->assertEquals('one', $plugin->tag());

    //     $plugin = $instance->plugin('two');
    //     $this->assertInstanceOf(Plugin::class, $plugin);
    //     $this->assertEquals('Two', $plugin->name());
    //     $this->assertEquals('two', $plugin->tag());
    // }

    // /** @test */
    // public function registerTheme()
    // {
    //     $instance = Handler::instance()->flush();
    //     $instance->registerTheme(ServiceProviderOne::class);
    //     $instance->registerTheme(ServiceProviderTwo::class);
    //     $this->assertCount(2, $instance->allThemes());

    //     // Não pode haver duplicidade de temas
    //     $instance->registerTheme(ServiceProviderTwo::class);
    //     $this->assertCount(2, $instance->allThemes());

    //     // Obtém o tema pelo provider
    //     $theme = $instance->theme(ServiceProviderTwo::class);
    //     $this->assertInstanceOf(Theme::class, $theme);
    //     $this->assertEquals('Two', $theme->name());
    //     $this->assertEquals('two', $theme->tag());

    //     // Obtém o tema pela tag
    //     $theme = $instance->theme('one');
    //     $this->assertInstanceOf(Theme::class, $theme);
    //     $this->assertEquals('One', $theme->name());
    //     $this->assertEquals('one', $theme->tag());
        
    //     $theme = $instance->theme('two');
    //     $this->assertInstanceOf(Theme::class, $theme);
    //     $this->assertEquals('Two', $theme->name());
    //     $this->assertEquals('two', $theme->tag());
    // }

    // /** @test */
    // public function currentPlugin()
    // {
    //     $instance = Handler::instance();

    //     $instance->registerPlugin(ServiceProviderOne::class);
    //     $instance->registerPlugin(ServiceProviderTwo::class);
    //     $this->assertNull($instance->currentPlugin());

    //     // Quando o usuario acessar uma rota, o módulo implemenetado deverá notificar o Core 
    //     // para que seja possível identifica o modulo em execução
    //     $instance->setCurrentPlugin(ServiceProviderOne::class);
    //     $this->assertNotNull($instance->currentPlugin());
    //     $this->assertEquals('One', $instance->currentPlugin()->name());
    //     $this->assertEquals('one', $instance->currentPlugin()->tag());

    //     $instance->setCurrentPlugin(ServiceProviderTwo::class);
    //     $this->assertEquals('Two', $instance->currentPlugin()->name());
    //     $this->assertEquals('two', $instance->currentPlugin()->tag());
    // }

    // /** @test */
    // public function currentPluginInvalid()
    // {
    //     $this->expectException(Exception::class);

    //     $instance = Handler::instance();
    //     $instance->registerPlugin(ServiceProviderOne::class);
    //     $instance->registerPlugin(ServiceProviderTwo::class);
    //     $this->assertNull($instance->currentPlugin());

    //     // Módulo inexistente
    //     $instance->setCurrentPlugin('xxx');
    // }

    // /** @test */
    // public function activateTheme()
    // {
    //     $instance = Handler::instance();
        
    //     $this->assertInstanceOf(ThemeCore::class, $instance->activeTheme());

    //     $instance->registerTheme(ServiceProviderOne::class);
    //     $instance->registerTheme(ServiceProviderTwo::class);
    //     $this->assertInstanceOf(Theme::class, $instance->activeTheme());
    //     $this->assertEquals('One', $instance->activeTheme()->name());
    //     $this->assertEquals('one', $instance->activeTheme()->tag());

    //     // De alguma forma, o sistema deverá ser capaz de setar o tema atualmente em uso
    //     // seja através de uma configuração implementada ou diretamente no código fonte

    //     $instance->setActiveTheme(ServiceProviderTwo::class);
    //     $this->assertEquals('Two', $instance->activeTheme()->name());
    //     $this->assertEquals('two', $instance->activeTheme()->tag());

    //     $instance->setActiveTheme('core');
    //     $this->assertEquals('Core', $instance->activeTheme()->name());
    //     $this->assertEquals('core', $instance->activeTheme()->tag());
    //     $this->assertEquals([], $instance->activeTheme()->scriptsTop());
    //     $this->assertEquals([], $instance->activeTheme()->scriptsBottom());
    //     $this->assertEquals([], $instance->activeTheme()->styles());
    // }

    // /** @test */
    // public function activateThemeInvalid()
    // {
    //     $this->expectException(Exception::class);

    //     $instance = Handler::instance();
    //     $this->assertInstanceOf(ThemeCore::class, $instance->activeTheme());

    //     // Tema inexistente
    //     $instance->setActiveTheme('xxx');
    // }

    // /** @test */
    // public function resolveAssets()
    // {
    //     $instance = Handler::instance();
    //     $instance->registerPlugin(ServiceProviderTwo::class);
    //     $this->assertNull($instance->currentPlugin());
    //     $this->assertInstanceOf(ThemeCore::class, $instance->activeTheme());

    //     // Assets padrões
    //     $this->assertCount(1, $instance->scriptsTop());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scriptsTop()[0]);
    //     $this->assertCount(1, $instance->scripts());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scripts()[0]);
    //     $this->assertCount(1, $instance->styles());
    //     $this->assertEquals('/modules/core/css/core.css', $instance->styles()[0]);

    //     // Veja: tests-module/files/Fake/ModuleOne/config/module_one.php
    //     $instance->registerTheme(ServiceProviderOne::class);

    //     $this->assertCount(2, $instance->scriptsTop());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scriptsTop()[0]);
    //     $this->assertEquals('/themes/one/js/legal.js', $instance->scriptsTop()[1]);
    //     $this->assertCount(1, $instance->scriptsBottom());
    //     $this->assertEquals('/themes/one/js/module.js', $instance->scriptsBottom()[0]);
    //     $this->assertCount(3, $instance->scripts());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scripts()[0]);
    //     $this->assertEquals('/themes/one/js/legal.js', $instance->scripts()[1]);
    //     $this->assertEquals('/themes/one/js/module.js', $instance->scripts()[2]);
    //     $this->assertCount(2, $instance->styles());
    //     $this->assertEquals('/modules/core/css/core.css', $instance->styles()[0]);
    //     $this->assertEquals('/themes/one/css/module.css', $instance->styles()[1]);

    //     // Veja: tests-module/files/Fake/ModuleTwo/config/module_two.php
    //     $instance->setCurrentPlugin(ServiceProviderTwo::class);

    //     $this->assertCount(2, $instance->scriptsTop());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scriptsTop()[0]);
    //     $this->assertEquals('/themes/one/js/legal.js', $instance->scriptsTop()[1]);

    //     $this->assertCount(3, $instance->scriptsBottom());
    //     $this->assertEquals('/themes/one/js/module.js', $instance->scriptsBottom()[0]);
    //     $this->assertEquals('/modules/two/js/shorenaitis.js', $instance->scriptsBottom()[1]);
    //     $this->assertEquals('/modules/two/js/birineiders.js', $instance->scriptsBottom()[2]);

    //     $this->assertCount(5, $instance->scripts());
    //     $this->assertEquals('/modules/core/js/core.js', $instance->scripts()[0]);
    //     $this->assertEquals('/themes/one/js/legal.js', $instance->scripts()[1]);
    //     $this->assertEquals('/themes/one/js/module.js', $instance->scripts()[2]);
    //     $this->assertEquals('/modules/two/js/shorenaitis.js', $instance->scripts()[3]);
    //     $this->assertEquals('/modules/two/js/birineiders.js', $instance->scripts()[4]);

    //     // Veja: tests-module/files/Fake/ModuleOne/config/module_one.php
    //     $this->assertCount(3, $instance->styles());
    //     $this->assertEquals('/modules/core/css/core.css', $instance->styles()[0]);
    //     $this->assertEquals('/themes/one/css/module.css', $instance->styles()[1]);
    //     $this->assertEquals('/modules/two/css/shoooo.css', $instance->styles()[2]);
    // }
}
