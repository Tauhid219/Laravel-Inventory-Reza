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

        // 1. Basic sub-category query and relation loading
        $query = SubCategory::query()
            ->with(['category', 'products'])
            ->search($this->search);

        // 2. Dynamic role-based filtering for sub-categories
        if (!$user->hasRole(['super-admin', 'admin', 'demo-admin'])) {
            // Get all current role names assigned to the user
            $userRoles = $user->getRoleNames();

            /**
             * Filter sub-categories by category where role_name matches user roles
             * SubCategory -> belongs to category -> where role_name matches
             */
            $query->whereHas('category', function ($q) use ($userRoles) {
                $q->whereIn('role_name', $userRoles);
            });
        }

        // 3. Fetch the filtered and sorted sub-categories
        $subCategories = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

        return view('livewire.tables.sub-category-table', [
            'subCategories' => $subCategories,
        ]);
    }
}
