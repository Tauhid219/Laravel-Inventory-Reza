<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\Category;
use App\Models\Order;
use Livewire\WithPagination;

class OrderV2Table extends Component
{
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $sortField = 'id';

    public $sortAsc = false;

    public $selectedCategory = null;

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

        // Start the query for orders
        $query = Order::query()->with([
            'customer',
            'details.product.category',
            'details.product.subCategory'
        ]);

        // 2. Define a mapping of roles to category names
        $roleMapping = [
            'passive-admin' => 'Passive',
            'printer-admin' => 'Printer',
            'scanner-admin' => 'Scanner',
            'server-admin' => 'Server',
            'storage-admin' => 'Storage',
            'server-accessories-admin' => 'Server Accessories',
            'network-wired-admin' => 'Network Wired',
            'network-tools-admin' => 'Network Tools',
            'network-wireless-admin' => 'Network Wireless',
            'network-security-admin' => 'Network Security',
            'general-others-admin' => 'General Others',
            'office-accessories-admin' => 'Office Accessories',
            'office-stationeries-admin' => 'Office Stationeries',
        ];

        // 3. Apply filters based on user roles
        if ($user->hasRole('super-admin|admin')) {
            // Super Admin or Admin sees all orders
            $this->selectedCategory = null;
        } else {
            $hasMatchingRole = false;

            foreach ($roleMapping as $role => $categoryName) {
                if ($user->hasRole($role)) {
                    $query->whereHas('details.product.category', function ($q) use ($categoryName) {
                        $q->where('name', $categoryName);
                    });
                    $hasMatchingRole = true;
                    break; // Exit the loop once a match is found
                }
            }

            // If no matching role is found, show no data
            if (!$hasMatchingRole) {
                $query->whereRaw('1 = 0');
            }
        }

        // 4. Execute the query with search, sorting, and pagination
        $orders = $query->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.order-v2-table', [
            'orders' => $orders,
        ]);
    }
}
