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

        if ($user->hasRole('super-admin|admin')) {
            $this->categories = Category::all();
        } elseif ($user->hasRole('passive-admin')) {
            $categoryId = Category::where('name', 'Passive')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('printer-admin')) {
            $categoryId = Category::where('name', 'Printer')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('scanner-admin')) {
            $categoryId = Category::where('name', 'Scanner')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('server-admin')) {
            $categoryId = Category::where('name', 'Server')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('storage-admin')) {
            $categoryId = Category::where('name', 'Storage')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('server-accessories-admin')) {
            $categoryId = Category::where('name', 'Server Accessories')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('network-wired-admin')) {
            $categoryId = Category::where('name', 'Network Wired')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('network-tools-admin')) {
            $categoryId = Category::where('name', 'Network Tools')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('network-wireless-admin')) {
            $categoryId = Category::where('name', 'Network Wireless')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('network-security-admin')) {
            $categoryId = Category::where('name', 'Network Security')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('general-others-admin')) {
            $categoryId = Category::where('name', 'General Others')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('office-accessories-admin')) {
            $categoryId = Category::where('name', 'Office Accessories')->first()->id;
            $query->where('category_id', $categoryId);
        } elseif ($user->hasRole('office-stationeries-admin')) {
            $categoryId = Category::where('name', 'Office Stationeries')->first()->id;
            $query->where('category_id', $categoryId);
        } else {
            $this->categories = [];  // Empty array, no categories loaded
        }

        $products = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.product-table', [
            'products' => $products,
        ]);

        // return view('livewire.tables.product-table', [
        //     'products' => Product::query()
        //         ->with(['category', 'unit'])
        //         ->search($this->search)
        //         ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        //         ->paginate($this->perPage),
        // ]);
    }
}
