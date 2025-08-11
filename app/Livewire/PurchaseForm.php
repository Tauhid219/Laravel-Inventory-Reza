<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PurchaseForm extends Component
{
    public $selectedCategory = null;
    public $selectedSubCategory = null;

    public $categories = [];
    public $subCategories = [];
    public $products = [];
    private $product;

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

        if ($user->hasRole('super-admin|admin')) {
            $this->categories = Category::all();
        } elseif ($user->hasRole('passive-admin')) {
            $categoryId = Category::where('name', 'Passive')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('printer-admin')) {
            $categoryId = Category::where('name', 'Printer')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('scanner-admin')) {
            $categoryId = Category::where('name', 'Scanner')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('server-admin')) {
            $categoryId = Category::where('name', 'Server')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('storage-admin')) {
            $categoryId = Category::where('name', 'Storage')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('server-accessories-admin')) {
            $categoryId = Category::where('name', 'Server Accessories')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('network-wired-admin')) {
            $categoryId = Category::where('name', 'Network Wired')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('network-tools-admin')) {
            $categoryId = Category::where('name', 'Network Tools')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('network-wireless-admin')) {
            $categoryId = Category::where('name', 'Network Wireless')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('network-security-admin')) {
            $categoryId = Category::where('name', 'Network Security')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('general-others-admin')) {
            $categoryId = Category::where('name', 'General Others')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('office-accessories-admin')) {
            $categoryId = Category::where('name', 'Office Accessories')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } elseif ($user->hasRole('office-stationeries-admin')) {
            $categoryId = Category::where('name', 'Office Stationeries')->first()->id;
            $this->categories = Category::where('id', $categoryId)->get();
        } else {
            $this->categories = [];  // Empty array, no categories loaded
        }

        $this->allProducts = Product::select('id', 'name', 'quantity', 'buying_price')->with(['category', 'subCategory'])->get();
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

        return view('livewire.purchase-form', [
            'subtotal' => $total,
            'categories' => $this->categories,
            'subCategories' => $this->subCategories,
            'products' => $this->products,
            'total' => $total * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100),
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

        $this->invoiceProducts[$index]['product_name'] = $product->name;
        $this->invoiceProducts[$index]['product_price'] = $product->buying_price;
        $this->invoiceProducts[$index]['category_name'] = $product->category->name;  // Add category name
        // $this->invoiceProducts[$index]['subcategory_name'] = $product->subCategory->name;  // Add subcategory name
        $this->invoiceProducts[$index]['subcategory_name'] = $product->subCategory ? $product->subCategory->name : 'N/A';  // Check if subcategory exists
        $this->invoiceProducts[$index]['is_saved'] = true;
    }

    public function removeProduct($index): void
    {
        unset($this->invoiceProducts[$index]);

        $this->invoiceProducts = array_values($this->invoiceProducts);
    }
}
