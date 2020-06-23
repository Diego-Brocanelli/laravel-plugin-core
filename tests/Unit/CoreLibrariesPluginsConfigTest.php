<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Plugins\Config;
use InvalidArgumentException;
use \PHPUnit\Framework\TestCase;

// Atenção!
// Os testes devem extender 'Tests\Plugin\TestCase'!!
// Este é um caso a parte para testar o mecanismo principal 

class CoreLibrariesPluginsConfigTest extends TestCase
{
    /** @test */
    public function invalidPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $config = new Config(__DIR__ . '/../files/not-exixts');
    }

    /** @test */
    public function loading()
    {
        $config = new Config(__DIR__ . '/../files/module_path/config');

        // Os dois arquivos de configuração em tests-module/files/config devem estar presentes
        $this->assertArrayHasKey('config_one.theme', $config->all());
        $this->assertArrayHasKey('config_two.theme', $config->all());
        $this->assertCount(6, $config->all());
    }

    /** @test */
    public function accessing()
    {
        $params = new Config(__DIR__ . '/../files/module_path/config');

        // A chamada de parâmetros é efetuada usando o namespace + notação pontuada do Laravel
        // ou seja, o parâmetro theme do arquivo 'config_one.php' será invocado por 'config_one.theme'
        $this->assertEquals('shore_one', $params->param('config_one.theme'));
        $this->assertEquals('shore_two', $params->param('config_two.theme'));

        // Os parâmetros do primeiro arquivo carregado não precisa de namespace
        // ou seja, o parâmetro theme do arquivo 'config_one.php' pode ser invocado diretamente por 'theme'
        $this->assertEquals('shore_one', $params->param('theme'));

        // Os parâmetros inexistentes retornam nulo
        $this->assertNull($params->param('not_exists'));
        $this->assertNull($params->param('config_two.not_exists'));
    }
}
