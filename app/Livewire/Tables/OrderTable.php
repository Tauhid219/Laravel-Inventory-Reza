<?php

namespace App\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
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

        // 1. Basic order query and relation loading
        $query = Order::query()->with([
            'customer',
            'details.product.category',
            'details.product.subCategory'
        ]);

        // 2. Dynamic role-based filtering
        if (!$user->hasRole(['super-admin', 'admin'])) {
            $userRoles = $user->getRoleNames(); // Get all current role names assigned to the user

            /**
             * Using nested whereHas:
             * Order -> has details -> which has product -> which has category -> where role_name matches user roles
             */
            $query->whereHas('details.product.category', function ($q) use ($userRoles) {
                $q->whereIn('role_name', $userRoles);
            });
        }

        // 3. Execute query with search, sorting, and pagination
        $orders = $query->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.order-table', [
            'orders' => $orders,
        ]);
    }
}
