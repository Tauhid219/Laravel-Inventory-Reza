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

    // public function mount()
    // {
    //     $this->categories = \App\Models\Category::all();
    // }

    public function mount()
    {
        $user = auth()->user();

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

        $query = Category::query();

        if (!$user->hasRole('super-admin|admin')) {
            $userCategoryName = null;

            foreach ($roleMapping as $role => $categoryName) {
                if ($user->hasRole($role)) {
                    $userCategoryName = $categoryName;
                    break;
                }
            }

            if ($userCategoryName) {
                $query->where('name', $userCategoryName);
            } else {
                // if the user has no matching role, return an empty collection
                $query->whereRaw('1 = 0');
            }
        }

        $this->categories = $query->get();

        // if there is only one category, set it as the selected category
        if ($this->categories->count() === 1) {
            $this->category_id = $this->categories->first()->id;
        }
    }
    public function render()
    {
        return view('livewire.select-category');
    }
}
