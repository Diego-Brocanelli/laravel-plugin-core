<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

class ThemeCore extends Theme
{
    public static function factory(): ThemeCore
    {
        $corePath = realpath(__DIR__ . '/../../../');
        return new ThemeCore('Core', $corePath);
    }
}
