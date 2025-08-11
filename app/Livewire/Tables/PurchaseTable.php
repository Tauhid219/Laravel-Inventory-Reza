<?php

namespace App\Livewire\Tables;

use App\Models\Category;
use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseTable extends Component
{
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $sortField = 'purchase_no';

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
        // return view('livewire.tables.purchase-table', [
        //     'purchases' => Purchase::query()
        //         ->with('supplier')
        //         ->search($this->search)
        //         ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
        //         ->paginate($this->perPage),
        // ]);

        $user = auth()->user();

        // Start the query to fetch purchases
        $query = Purchase::query()->with('supplier'); // Assuming Purchase has a relationship with Supplier

        // Filter purchases based on user role and associated category
        if ($user->hasRole('super-admin|admin')) {
            // Super Admin or Admin sees all purchases
            $this->selectedCategory = null;
        } elseif ($user->hasRole('passive-admin')) {
            $categoryId = Category::where('name', 'Passive')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('printer-admin')) {
            $categoryId = Category::where('name', 'Printer')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('scanner-admin')) {
            $categoryId = Category::where('name', 'Scanner')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('server-admin')) {
            $categoryId = Category::where('name', 'Server')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('storage-admin')) {
            $categoryId = Category::where('name', 'Storage')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('server-accessories-admin')) {
            $categoryId = Category::where('name', 'Server Accessories')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('network-wired-admin')) {
            $categoryId = Category::where('name', 'Network Wired')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('network-tools-admin')) {
            $categoryId = Category::where('name', 'Network Tools')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('network-wireless-admin')) {
            $categoryId = Category::where('name', 'Network Wireless')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('network-security-admin')) {
            $categoryId = Category::where('name', 'Network Security')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('general-others-admin')) {
            $categoryId = Category::where('name', 'General Others')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('office-accessories-admin')) {
            $categoryId = Category::where('name', 'Office Accessories')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } elseif ($user->hasRole('office-stationeries-admin')) {
            $categoryId = Category::where('name', 'Office Stationeries')->first()->id;
            $query->whereHas('details.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        } else {
            // No category for other roles
            $query->whereHas('details.product.category', function ($q) {
                $q->where('id', -1); // Invalid category ID to return no purchases
            });
        }

        // Fetch the purchases based on the query conditions
        $purchases = $query->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.purchase-table', [
            'purchases' => $purchases,
        ]);
    }
}
