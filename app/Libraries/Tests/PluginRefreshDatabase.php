<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Tests;

use App\Plugin\Core\Libraries\Plugins\Handler;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait PluginRefreshDatabase
{
    use RefreshDatabase;

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');

        // Executa as migrations do plugin atual
        $plugin = Handler::instance()->plugin($this->serviceProvider());
        $migrationsPath = implode(DIRECTORY_SEPARATOR, [$plugin->path(), 'database', 'migrations']);
        $this->artisan('migrate', ['--path' => $migrationsPath]);

        $this->app[Kernel::class]->setArtisan(null);
    }
}
