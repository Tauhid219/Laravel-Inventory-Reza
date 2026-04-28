<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class OrderForm extends Component
{
    public $selectedCategory = null;
    public $selectedSubCategory = null;

    public $categories = [];
    public $subCategories = [];
    public $products = [];
    private $product;
    public $manualTotal = null;

    // Static roleMapping removed to enable dynamic role management

    #[Validate('Required')]
    public int $taxes = 0;

    public array $invoiceProducts = [];

    #[Validate('required', message: 'Please select products')]
    public Collection $allProducts;

    public function mount(): void
    {
        $user = auth()->user();

        // 1. Initialize queries
        $categoryQuery = Category::query();
        $productQuery = Product::select('id', 'name', 'quantity', 'buying_price', 'category_id', 'sub_category_id')
            ->with(['category', 'subCategory']);

        // 2. Apply dynamic role-based filtering logic
        if (!$user->hasRole(['super-admin', 'admin'])) {
            // Get all role names assigned to the user
            $userRoles = $user->getRoleNames();

            // Filter categories based on role_name column
            $categoryQuery->whereIn('role_name', $userRoles);

            // Filter products: Ensure only products from allowed categories are accessible
            $productQuery->whereHas('category', function ($q) use ($userRoles) {
                $q->whereIn('role_name', $userRoles);
            });
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

        return view('livewire.order-form', [
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
