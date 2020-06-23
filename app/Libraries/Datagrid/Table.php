<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Datagrid;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class Table
{
    private $total;
    private $filtered;
    private $data;

    public function fromArray(array $data): Table
    {
        // Implementação apenas de exemplo
        // deve ser evoluída para ser utilizada corretamente
        $this->data     = $data;
        $this->total    = count($data);
        $this->filtered = count($data);
        return $this;
    }

    public function fromCollection(Collection $data): Table
    {
        return $this;
    }

    public function fromBuilder(Builder $data): Table
    {
        return $this;
    }

    public function response(): array
    {
        return [
            "draw" => 1,
            "recordsTotal" => 57,
            "recordsFiltered" => 57,
            "data" => $this->data
        ];
    }
}
