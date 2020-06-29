<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

class ThemeCore extends Theme
{
    public static function factory(): ThemeCore
    {
        $corePath = realpath(__DIR__ . '/../../../');
        $instance =  new ThemeCore('Core', $corePath);
        $instance->addStyle('theme.css');
        // $instance->addScriptBottom('theme.js');
        return $instance;
    }
}
