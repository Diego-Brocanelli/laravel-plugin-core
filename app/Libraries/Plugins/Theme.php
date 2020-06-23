<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Plugins;

class Theme extends Plugin
{
    protected function formatPrefixUrl(): string
    {
        return "/themes/" . $this->tag();
    }
}
