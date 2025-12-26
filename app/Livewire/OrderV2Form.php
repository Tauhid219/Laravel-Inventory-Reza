<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OrderV2Form extends Component
{
    public $selectedCategory = null;
    public $selectedSubCategory = null;

    public $categories = [];
    public $subCategories = [];
    public $products = [];
    private $product;
    public $manualTotal = null;

    // Central role mapping
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

    #[Validate('Required')]
    public int $taxes = 0;

    public array $invoiceProducts = [];

    #[Validate('required', message: 'Please select products')]
    public Collection $allProducts;

    public function mount(): void
    {
        // $this->categories = Category::all();

        // Load categories based on the user's role
        $user = auth()->user();

        // 1. Initialize queries
        $categoryQuery = Category::query();
        $productQuery = Product::select('id', 'name', 'quantity', 'buying_price')
            ->with(['category', 'subCategory']);

        // 2. Apply role-based filtering logic
        if (!$user->hasRole('super-admin|admin')) {
            $userCategoryName = null;
            foreach ($this->roleMapping as $role => $categoryName) {
                if ($user->hasRole($role)) {
                    $userCategoryName = $categoryName;
                    break;
                }
            }

            if ($userCategoryName) {
                // Filter categories
                $categoryQuery->where('name', $userCategoryName);

                // Filter products to ensure only allowed products can be added
                $productQuery->whereHas('category', function ($q) use ($userCategoryName) {
                    $q->where('name', $userCategoryName);
                });
            } else {
                // If no matching role is found, return empty results
                $categoryQuery->whereRaw('1 = 0');
                $productQuery->whereRaw('1 = 0');
            }
        }

        $this->categories = $categoryQuery->get();
        $this->allProducts = $productQuery->get();
    }

    public function onCategoryUpdated($categoryId)
    {
        $this->subCategories = SubCategory::where('category_id', $categoryId)->get();
        $this->products = Product::where('category_id', $categoryId)->get();
        $this->selectedSubCategory = null;  // reset sub-category
        // $this->products = [];  // reset products
    }

    public function onSubCategoryUpdated($subCategoryId)
    {
        $this->products = Product::where('sub_category_id', $subCategoryId)->get();
    }

    public function render(): View
    {
        $total = 0;

        foreach ($this->invoiceProducts as $invoiceProduct) {
            if ($invoiceProduct['is_saved'] && $invoiceProduct['product_price'] && $invoiceProduct['quantity']) {
                $total += $invoiceProduct['product_price'] * $invoiceProduct['quantity'];
            }
        }

        // If manualTotal is set, use it; otherwise, calculate total
        $finalTotal = $this->manualTotal !== null && $this->manualTotal !== ''
            ? $this->manualTotal
            : $total * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100);

        return view('livewire.order-v2-form', [
            'subtotal' => $total,
            'categories' => $this->categories,
            'subCategories' => $this->subCategories,
            'products' => $this->products,
            'total' => $finalTotal,
        ]);
    }

    public function addProduct(): void
    {
        foreach ($this->invoiceProducts as $key => $invoiceProduct) {
            if (!$invoiceProduct['is_saved']) {
                $this->addError('invoiceProducts.' . $key, 'This line must be saved before creating a new one.');

                return;
            }
        }

        $this->invoiceProducts[] = [
            'product_id' => '',
            'quantity' => 1,
            'is_saved' => false,
            'product_name' => '',
            'product_price' => 0,
            'product_quantity' => 0,  // The product's available stock quantity will come from the model
        ];
    }

    public $product_quantity = 0;  // Default quantity, initially 0

    // This function will be called when a product is selected
    public function onProductSelected($index)
    {
        $product = $this->allProducts->find($this->invoiceProducts[$index]['product_id']);

        if ($product) {
            // Set the product quantity from the model to the invoice product
            $this->invoiceProducts[$index]['product_quantity'] = $product->quantity;
        }
    }

    public function editProduct($index): void
    {
        foreach ($this->invoiceProducts as $key => $invoiceProduct) {
            if (!$invoiceProduct['is_saved']) {
                $this->addError('invoiceProducts.' . $key, 'This line must be saved before editing another.');

                return;
            }
        }

        $this->invoiceProducts[$index]['is_saved'] = false;
    }

    public function saveProduct($index): void
    {
        $this->resetErrorBag();
        $product = $this->allProducts->find($this->invoiceProducts[$index]['product_id']);

        if ($product) {
            $this->invoiceProducts[$index]['product_name'] = $product->name;
            $this->invoiceProducts[$index]['product_price'] = $product->buying_price;
            $this->invoiceProducts[$index]['category_name'] = $product->category->name;
            $this->invoiceProducts[$index]['subcategory_name'] = $product->subCategory ? $product->subCategory->name : 'N/A';
            $this->invoiceProducts[$index]['is_saved'] = true;
        }
    }

    public function removeProduct($index): void
    {
        unset($this->invoiceProducts[$index]);

        $this->invoiceProducts = array_values($this->invoiceProducts);
    }
}
