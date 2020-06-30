<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Plugins\Plugin;
use App\Plugin\Core\Providers\ServiceProvider;
use Carbon\Laravel\ServiceProvider as LaravelServiceProvider;
use InvalidArgumentException;
use \PHPUnit\Framework\TestCase;

// Atenção!
// Os testes devem extender 'Tests\Plugin\TestCase'!!
// Este é um caso a parte para testar o mecanismo principal 

class CoreLibrariesPluginsPluginTest extends TestCase
{
    /** @test */
    public function pathNotExists()
    {
        $this->expectException(InvalidArgumentException::class);
        new Plugin('Teste', __DIR__ . '/../files/not-exixts');
    }

    /** @test */
    public function invalidPath()
    {
        $this->expectException(InvalidArgumentException::class);
        new Plugin('Teste', __DIR__ . '/../files');
    }

    /** @test */
    // public function accessing()
    // {
    //     $path = __DIR__ . '/../files/module_path';

    //     $instance = new Plugin('Teste', $path, [ServiceProvider::class]);
    //     $this->assertEquals('Teste', $instance->name());
    //     $this->assertEquals('test_one', $instance->tag());
    //     $this->assertEquals($path, $instance->path());
    //     $this->assertEquals('shore_one', $instance->config()->param('config_one.theme'));
    //     $this->assertEquals('shore_two', $instance->config()->param('config_two.theme'));
    //     $this->assertCount(1, $instance->providers());
    //     $this->assertEquals(ServiceProvider::class, $instance->providers()[0]);

    //     // Não pode haver duplicidade de providers
    //     $instance->addProvider(ServiceProvider::class);
    //     $this->assertCount(1, $instance->providers());

    //     $instance->addProvider(LaravelServiceProvider::class);
    //     $this->assertCount(2, $instance->providers());

    // }


}
