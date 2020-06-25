<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Panel;

class HeaderMenu extends Sidebar
{
    static $instance;
    
    public static function instance(): HeaderMenu
    {
        if (static::$instance === null) {
            static::$instance = new HeaderMenu();
        }

        return static::$instance;
    }
}
