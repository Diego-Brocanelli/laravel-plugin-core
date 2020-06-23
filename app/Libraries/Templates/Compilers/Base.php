<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Templates\Compilers;

trait Base
{
    protected function registerDirective(string $name, string $implementation)
    {
        $this->directivesList[$name] = $implementation;
        return $this;
    }

    protected function clearExpression(string $expression): string
    {
        return trim($expression, "'");;
    }
}
