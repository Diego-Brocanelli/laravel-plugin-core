<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Tests;

use App\Plugin\Core\Libraries\Composer\Scripts;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class ModuleTestCase extends BaseTestCase
{
    use CreatesApplication;

    abstract protected function serviceProvider(): string;
    
    protected function flushLaravelCache()
    {
        Scripts::instance()->clearCache();
    }
}
