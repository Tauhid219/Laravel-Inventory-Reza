<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class SelectCategory extends Component
{
    public $category_id;
    public $categories;

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
    ];

    public function mount()
    {
        $user = auth()->user();
        $query = Category::query();

        // 1. Dynamic role-based filtering for categories
        if (!$user->hasRole(['super-admin', 'admin'])) {
            // Get all role names assigned to the user
            $userRoles = $user->getRoleNames();

            // Filter categories where role_name matches user roles
            $query->whereIn('role_name', $userRoles);
        }

        $this->categories = $query->get();

        // 2. Auto-select if there is only one category available
        if ($this->categories->count() === 1) {
            $this->category_id = $this->categories->first()->id;
        }
    }

    public function render()
    {
        return view('livewire.select-category');
    }
}
