<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Panel\Breadcrumb;
use App\Plugin\Core\Libraries\Panel\Entry;
use Error;
use Tests\TestCase;

class CoreLibrariesPanelBreadcrumbTest extends TestCase
{
    protected function setUp(): void
    {
        // Importante:
        // Sempre que sobrescrever o setUp, caso esteja-se usando o Tests\Module\TestCase
        // é preciso invocar a sobrecarga, pois contém implementações originais o Laravel
        parent::setUp();

        // Zera o manipulador para não haver interferência nos testes
        Breadcrumb::instance()->flush();

        $this->flushLaravelCache();
    }

    /** @test */
    public function singleton()
    {
        $this->expectException(Error::class);
        new Breadcrumb();
    }

    /** @test */
    public function append()
    {
        $instance = Breadcrumb::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Breadcrumb::instance()->append($entry);

        // Existe um único item
        $this->assertCount(1, $instance->allEntries());
        $this->assertArrayHasKey('item-1', $instance->allEntries());

        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Breadcrumb::instance()->append($entry);
        $entry = new Entry('Item 3', 'http://www.google.com/url3');
        Breadcrumb::instance()->append($entry);

        // Os próximos itens devem ter sido adicinados no final da pilha
        $this->assertCount(3, $instance->allEntries());
        $stack = array_values($instance->allEntries());
        $this->assertEquals('item-1', $stack[0]->slug());
        $this->assertEquals('item-2', $stack[1]->slug());
        $this->assertEquals('item-3', $stack[2]->slug());
    }

    /** @test */
    public function getter()
    {
        $instance = Breadcrumb::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Breadcrumb::instance()->append($entry);
        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Breadcrumb::instance()->append($entry);

        // Existem dois itens
        $this->assertCount(2, $instance->allEntries());
        
        $retrieved = Breadcrumb::instance()->entry('item-1');
        $this->assertInstanceOf(Entry::class, $retrieved);

        $retrieved = Breadcrumb::instance()->entry('item-not-exists');
        $this->assertNull($retrieved);
    }

    /** @test */
    public function remove()
    {
        $instance = Breadcrumb::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Breadcrumb::instance()->append($entry);
        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Breadcrumb::instance()->append($entry);

        // Existem dois itens
        $this->assertCount(2, $instance->allEntries());
        $this->assertArrayHasKey('item-1', $instance->allEntries());
        $this->assertArrayHasKey('item-2', $instance->allEntries());

        Breadcrumb::instance()->remove('item-1');
        $this->assertCount(1, $instance->allEntries());
        $this->assertArrayNotHasKey('item-1', $instance->allEntries());
        $this->assertArrayHasKey('item-2', $instance->allEntries());

        Breadcrumb::instance()->remove('item-2');
        $this->assertCount(0, $instance->allEntries());
        $this->assertArrayNotHasKey('item-2', $instance->allEntries());
    }
}
