<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Composer\Parser;
use InvalidArgumentException;
use \PHPUnit\Framework\TestCase;

// Atenção!
// Os testes devem extender 'Tests\Module\TestCase'!!
// Este é um caso a parte para testar o mecanismo principal 

class CoreLibrariesComposerParserTest extends TestCase
{
    /** @test */
    public function invalidPath()
    {
        $this->expectException(InvalidArgumentException::class);
        new Parser(__DIR__ . '/../files/composer.json');
    }

    /** @test */
    public function invalidSintax()
    {
        $this->expectException(InvalidArgumentException::class);
        new Parser(__DIR__ . '/../files/composer_invalid_sintax.json');
    }

    /** @test */
    public function invalidParams()
    {
        $this->expectException(InvalidArgumentException::class);
        new Parser(__DIR__ . '/../files/composer_invalid_params.json');
    }

    /** @test */
    public function loading()
    {
        $config = new Parser(__DIR__ . '/../files/module_path/composer.json');
        $this->assertArrayHasKey('name', $config->all());
        $this->assertArrayHasKey('description', $config->all());
        $this->assertArrayHasKey('extra.laravel.aliases.core', $config->all());
        
        $this->assertEquals('exemplo/para-teste', $config->param('name'));
        $this->assertEquals('App\Module\Core\Providers\ModuleFacade', $config->param('extra.laravel.aliases.core'));
    }
}
