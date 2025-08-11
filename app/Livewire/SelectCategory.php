<?php

namespace App\Livewire;

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
        $this->categories = \App\Models\Category::all();
    }
    public function render()
    {
        return view('livewire.select-category');
    }
}
