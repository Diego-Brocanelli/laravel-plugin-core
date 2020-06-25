<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Panel;

use InvalidArgumentException;
use Illuminate\Support\Str;

/**
 * Esta é a classe que permite acesso as todos os plugins registrados na aplicação.
 * Ela funciona como uma API, para que qualquer plugin seja acessível de forma direta.
 */
class Entry
{
    const STATUS_COMMON = 'common';

    const STATUS_ACTIVE = 'active';

    const STATUS_DISABLED = 'disabled';

    private $label;

    private $slug;

    private $icon;

    private $url;

    private $status;

    private $children = [];

    public function __construct(string $label, ?string $url = null, string $icon = null)
    {
        $this->label  = $label;
        $this->slug   = Str::slug($label);
        $this->url    = $url ?? 'javascript:void(0);';
        $this->icon   = $icon ?? '';
        $this->status = self::STATUS_COMMON;
    }

    public function setUrl(string $url): Entry
    {
        $this->url = $url;
        return $this;
    }

    public function setIcon(string $class): Entry
    {
        $this->icon = $class;
        return $this;
    }

    public function setStatus(string $status = Entry::STATUS_COMMON): Entry
    {
        $allow = [Entry::STATUS_COMMON, Entry::STATUS_ACTIVE, Entry::STATUS_DISABLED];
        if (in_array($status, $allow) === false) {
            throw new InvalidArgumentException('Status inválido para a entrada de menu');
        }

        $this->status = $status;
        return $this;
    }

    public function appendChild(Entry $child): Entry
    {
        $this->children[$child->slug()] = $child;
        return $this;
    }

    public function prependChild(Entry $child): Entry
    {
        $this->children = array_merge([$child->slug() => $child], $this->children);
        return $this;
    }

    public function removeChild(string $slug): Entry
    {
        if (isset($this->children[$slug]) === true){
            unset($this->children[$slug]);
        }

        return $this;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function url(): string
    {
        if ($this->url[0] === '/' 
         || strpos($this->url, 'http') !== false 
         || strpos($this->url, 'void(') !== false
        ) {
            return $this->url;
        }

        return route($this->url);
    }

    public function icon(): string
    {
        return $this->icon ?? '';
    }

    public function status(): string
    {
        return $this->status;
    }

    public function hasChildren(): bool
    {
        return $this->children !== [];
    }

    public function children(): array
    {
        return $this->children;
    }

    public function toArray()
    {
        $entry = [
            'label' => $this->label(),
            'slug' => $this->slug(),
            'icon' =>  $this->icon(),
            'url' => $this->url(),
            'status' => $this->status()
        ];

        if ($this->hasChildren() === true) {
            $entry['children'] = array_map(fn($item) => $item->toarray(), $this->children());
        }

        return $entry;
    }
}
