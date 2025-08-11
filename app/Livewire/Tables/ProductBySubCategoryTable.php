<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use Livewire\WithPagination;

class ProductBySubCategoryTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'name';

    public $sortAsc = true;

    public $subCategory = null;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount($subCategory)
    {
        $this->subCategory = $subCategory;
    }

    public function render()
    {
        return view('livewire.tables.product-by-sub-category-table', [
            'products' => $this->subCategory->products()
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
