<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Templates\Handler;
use Error;
use InvalidArgumentException;
use Tests\TestCase;

class CoreLibrariesTemplatesHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        // Importante:
        // Sempre que sobrescrever o setUp, caso esteja-se usando o Tests\Plugin\TestCase
        // é preciso invocar a sobrecarga, pois contém implementações originais o Laravel
        parent::setUp();

        // Zera o manipulador para não haver interferência nos testes
        Handler::instance()->flush();

        $this->flushLaravelCache();
    }

    /** @test */
    public function singleton()
    {
        $this->expectException(Error::class);
        new Handler();
    }

    /** @test */
    public function registerInvalidPrototype()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("A view 'prototype.not-exists' correspondente ao protótipo 'prototype.not-exists' não existe no Módulo Core");
        $instance = Handler::instance();
        $instance->registerView('prototype.not-exists','view.not-exists');
    }

    /** @test */
    // public function registerInvalidView()
    // {
    //     $this->expectException(InvalidArgumentException::class);
    //     $this->expectExceptionMessage("A view 'view.not-exists' não foi encontrada no sistema e por isso não foi registrada");
    //     $instance = Handler::instance();
    //     $instance->registerView('core::unit_tests.test-body','view.not-exists');
    // }

    // /** @test */
    // public function register()
    // {
    //     $instance = Handler::instance();

    //     $this->assertCount(0, $instance->allReplaces());

    //     $instance->registerView('core::unit_tests.test-document', 'core::unit_tests.test-document-replaced');
    //     $this->assertCount(1, $instance->allReplaces());

    //     $this->assertEquals([
    //         "core::unit_tests.test-document" => "core::unit_tests.test-document-replaced"
    //     ], $instance->allReplaces());

    //     $instance->removeView('core::unit_tests.test-document-replaced');
    //     $this->assertCount(0, $instance->allReplaces());
    // }

    // /** @test */
    // public function noReplace()
    // {
    //     $instance = Handler::instance();

    //     // Por padrão, a view 'unit_tests.test-body' irá extender a 'core::unit_tests.test-document'
    //     $this->assertCount(0, $instance->allReplaces());
    //     $render = view('core::unit_tests.test-body')->with('name', 'legal')->render();
    //     $this->assertEquals('<html id="original" name="legal"> <body id="original" name="legal"></body> </html>', $render);
    // }

    // /** @test */
    // public function replace()
    // {
    //     $instance = Handler::instance();

    //     // O pai imediatamente acima deve ser substituído
    //     $instance->registerView('core::unit_tests.test-document', 'core::unit_tests.test-document-replaced');
    //     $this->assertCount(1, $instance->allReplaces());

    //     $render = view('core::unit_tests.test-document')->with('name', 'nice')->render();
    //     $this->assertEquals('<html id="replaced" name="nice"></html>', $render);
    // }

    // /** @test */
    // public function replaceDirectParent()
    // {
    //     $instance = Handler::instance();

    //     // O pai imediatamente acima deve ser substituído
    //     $instance->registerView('core::unit_tests.test-document', 'core::unit_tests.test-document-replaced');
    //     $this->assertCount(1, $instance->allReplaces());

    //     $render = view('core::unit_tests.test-body')->with('name', 'nice')->render();

    //     $this->assertEquals('<html id="replaced" name="nice"> <body id="original" name="nice"></body> </html>', $render);
    // }

    // /** @test */
    // public function replaceOldParent()
    // {
    //     $instance = Handler::instance();

    //     // O avô deve ser substituído
    //     $instance->registerView('core::unit_tests.test-document', 'core::unit_tests.test-document-replaced');
    //     $this->assertCount(1, $instance->allReplaces());
    //     $render = view('core::unit_tests.test-component')->with('name', 'nice')->render();
    //     $this->assertEquals('<html id="replaced" name="nice"> <body id="original" name="nice"> <div name="nice">Testado Componente</div> </body> </html>', $render);

    //     $this->flushLaravelCache();

    //     // Ambos são substrituidos
    //     $instance->registerView('core::unit_tests.test-body', 'core::unit_tests.test-body-replaced');
    //     $this->assertCount(2, $instance->allReplaces());
    //     $render = view('core::unit_tests.test-component')->with('name', 'nice')->render();
    //     $this->assertEquals('<html id="replaced" name="nice"> <body id="replaced" name="nice"> <div name="nice">Testado Componente</div> </body> </html>', $render);
    // }

    // /** @test */
    // public function replaceOnlyParent()
    // {
    //     $instance = Handler::instance();

    //     // Somente o pai deve ser substituído, mantendo o avô
    //     $instance->registerView('core::unit_tests.test-body', 'core::unit_tests.test-body-replaced');
    //     $this->assertCount(1, $instance->allReplaces());
    //     $render = view('core::unit_tests.test-component')->with('name', 'nice')->render();
    //     $this->assertEquals('<html id="original" name="nice"> <body id="replaced" name="nice"> <div name="nice">Testado Componente</div> </body> </html>', $render);
    // }

    // /** @test */
    // public function includeNoReplace()
    // {
    //     Handler::instance();
    //     $render = view('core::unit_tests.test-include')->with('name', 'Nice')->render();
    //     $this->assertEquals('<div> Original Nice </div>', $render);
    // }

    // /** @test */
    // public function includeReplaced()
    // {
    //     $instance = Handler::instance();

    //     $instance->registerView('core::unit_tests.test-included', 'core::unit_tests.test-included-replaced');
    //     $render = view('core::unit_tests.test-include')->with('name', 'Cool')->render();
    //     $this->assertEquals('<div> Replaced Cool </div>', $render);
    // }
}
