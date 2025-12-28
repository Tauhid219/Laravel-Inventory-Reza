<?php

namespace App\Livewire\Tables;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $perPage = 25;

    public $search = '';

    public $sortField = 'id';

    public $sortAsc = false;

    public $categories;

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $user = auth()->user();
        $query = Product::query()->with(['category', 'unit', 'subCategory'])->search($this->search);

        if (!$user->hasRole(['super-admin', 'admin'])) {
            $userRoles = $user->getRoleNames(); // Get all role names of the user

            $query->whereHas('category', function ($q) use ($userRoles) {
                $q->whereIn('role_name', $userRoles);
            });
        }

        $products = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->perPage);

        return view('livewire.tables.product-table', ['products' => $products]);
    }
}
