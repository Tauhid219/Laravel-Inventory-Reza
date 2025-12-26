<?php

namespace App\Livewire\Tables;

use App\Models\SubCategory;
use Livewire\Component;
use Livewire\WithPagination;

class SubCategoryTable extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $sortField = 'name';
    public $sortAsc = false;

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

        // 1. Initialize the query
        $query = SubCategory::query()
            ->with(['category', 'products'])
            ->search($this->search);

        // 2. Role-based filtering for sub-categories
        if (!$user->hasRole('super-admin|admin')) {
            $hasMatched = false;

            foreach ($this->roleMapping as $role => $categoryName) {
                if ($user->hasRole($role)) {
                    // Filter sub-categories by category name
                    $query->whereHas('category', function ($q) use ($categoryName) {
                        $q->where('name', $categoryName);
                    });
                    $hasMatched = true;
                    break;
                }
            }

            // If no roles matched, return no results
            if (!$hasMatched) {
                $query->whereRaw('1 = 0');
            }
        }

        // 3. Fetch the filtered and sorted sub-categories
        $subCategories = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.sub-category-table', [
            'subCategories' => $subCategories,
        ]);
    }
}
