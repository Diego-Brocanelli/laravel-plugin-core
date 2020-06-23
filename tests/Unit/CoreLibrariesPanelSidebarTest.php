<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Plugin\Core\Libraries\Panel\Entry;
use App\Plugin\Core\Libraries\Panel\Sidebar;
use Error;
use Tests\TestCase;

class CoreLibrariesPanelSidebarTest extends TestCase
{
    protected function setUp(): void
    {
        // Importante:
        // Sempre que sobrescrever o setUp, caso esteja-se usando o Tests\Module\TestCase
        // é preciso invocar a sobrecarga, pois contém implementações originais o Laravel
        parent::setUp();

        // Zera o manipulador para não haver interferência nos testes
        Sidebar::instance()->flush();

        $this->flushLaravelCache();
    }

    /** @test */
    public function singleton()
    {
        $this->expectException(Error::class);
        new Sidebar();
    }

    /** @test */
    public function append()
    {
        $instance = Sidebar::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Sidebar::instance()->append($entry);

        // Existe um único item
        $this->assertCount(1, $instance->allEntries());
        $this->assertArrayHasKey('item-1', $instance->allEntries());

        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Sidebar::instance()->append($entry);
        $entry = new Entry('Item 3', 'http://www.google.com/url3');
        Sidebar::instance()->append($entry);

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
        $instance = Sidebar::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Sidebar::instance()->append($entry);
        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Sidebar::instance()->append($entry);

        // Existem dois itens
        $this->assertCount(2, $instance->allEntries());
        
        $retrieved = Sidebar::instance()->entry('item-1');
        $this->assertInstanceOf(Entry::class, $retrieved);

        $retrieved = Sidebar::instance()->entry('item-not-exists');
        $this->assertNull($retrieved);
    }

    /** @test */
    public function prepend()
    {
        $instance = Sidebar::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Sidebar::instance()->append($entry);

        // Existe um único item
        $this->assertCount(1, $instance->allEntries());
        $this->assertArrayHasKey('item-1', $instance->allEntries());

        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Sidebar::instance()->prepend($entry);
        $entry = new Entry('Item 3', 'http://www.google.com/url3');
        Sidebar::instance()->prepend($entry);

        // Os próximos itens devem ter sido adicinados no inicio da pilha
        $this->assertCount(3, $instance->allEntries());
        $stack = array_values($instance->allEntries());
        $this->assertEquals('item-3', $stack[0]->slug());
        $this->assertEquals('item-2', $stack[1]->slug());
        $this->assertEquals('item-1', $stack[2]->slug());
    }

    /** @test */
    public function remove()
    {
        $instance = Sidebar::instance();

        $this->assertCount(0, $instance->allEntries());

        $entry = new Entry('Item 1', 'http://www.google.com/url1');
        Sidebar::instance()->append($entry);
        $entry = new Entry('Item 2', 'http://www.google.com/url2');
        Sidebar::instance()->append($entry);

        // Existem dois itens
        $this->assertCount(2, $instance->allEntries());
        $this->assertArrayHasKey('item-1', $instance->allEntries());
        $this->assertArrayHasKey('item-2', $instance->allEntries());

        Sidebar::instance()->remove('item-1');
        $this->assertCount(1, $instance->allEntries());
        $this->assertArrayNotHasKey('item-1', $instance->allEntries());
        $this->assertArrayHasKey('item-2', $instance->allEntries());

        Sidebar::instance()->remove('item-2');
        $this->assertCount(0, $instance->allEntries());
        $this->assertArrayNotHasKey('item-2', $instance->allEntries());
    }
}
