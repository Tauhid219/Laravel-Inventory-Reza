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

    // Define a mapping of roles to category names
    protected $roleMapping = [
        'passive-admin'            => 'Passive',
        'printer-admin'            => 'Printer',
        'scanner-admin'            => 'Scanner',
        'server-admin'             => 'Server',
        'storage-admin'            => 'Storage',
        'server-accessories-admin' => 'Server Accessories',
        'network-wired-admin'      => 'Network Wired',
        'network-tools-admin'      => 'Network Tools',
        'network-wireless-admin'   => 'Network Wireless',
        'network-security-admin'   => 'Network Security',
        'general-others-admin'     => 'General Others',
        'office-accessories-admin' => 'Office Accessories',
        'office-stationeries-admin'=> 'Office Stationeries',
    ];

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

        // 1. Start the query for products
        $query = Product::query()
            ->with(['category', 'unit', 'subCategory'])
            ->search($this->search);

        // 2. Apply role-based filtering
        if ($user->hasRole('super-admin|admin')) {
            $this->categories = Category::all();
        } else {
            $hasMatched = false;
            foreach ($this->roleMapping as $role => $categoryName) {
                if ($user->hasRole($role)) {
                    $query->whereHas('category', function($q) use ($categoryName) {
                        $q->where('name', $categoryName);
                    });
                    $hasMatched = true;
                    break; // Exit the loop once a match is found
                }
            }

            // If the user has no matching role, show empty results
            if (!$hasMatched) {
                $query->whereRaw('1 = 0');
            }
        }

        // 3. Pagination and Sorting
        $products = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.product-table', [
            'products' => $products,
        ]);
    }
}
