<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view product')->only(['index', 'show']);
        $this->middleware('permission:create product')->only(['create', 'store', 'generateUniqueCode']);
        $this->middleware('permission:update product')->only(['edit', 'update']);
        $this->middleware('permission:delete product')->only(['destroy']);
    }

    public function index()
    {
        return view('products.index');
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $query = Category::query();

        // Dynamic role-based filtering
        if (!$user->hasRole(['super-admin', 'admin'])) {
            $userRoles = $user->getRoleNames(); // User's current role names

            // Filter categories based on user's roles
            $query->whereIn('role_name', $userRoles);
        }

        $categories = $query->get();

        // Fetch sub-categories for the filtered categories
        $subCategories = SubCategory::whereIn('category_id', $categories->pluck('id'))->get();

        return view('products.create', [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'units' => Unit::all()
        ]);
    }

    public function getSubCategories($category_id)
    {
        $subCategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json(['subCategories' => $subCategories]);
    }

    public function store(StoreProductRequest $request)
    {
        // Check for duplicate product code
        $existingProduct = Product::where('code', $request->get('code'))->first();

        if ($existingProduct) {
            $newCode = $this->generateUniqueCode();
            $request->merge(['code' => $newCode]);
        }

        try {
            // Create product with validated fields and generated slug
            $product = Product::create(array_merge(
                $request->only([
                    'code',
                    'name',
                    'category_id',
                    'sub_category_id',
                    'unit_id',
                    'buying_price',
                    'selling_price',
                    'quantity_alert',
                    'tax',
                    'tax_type',
                    'notes',
                ]),
                [
                    'slug' => Str::slug($request->get('name')),
                ]
            ));

            // Handle image upload if present
            if ($request->hasFile('product_image')) {
                $file = $request->file('product_image');
                $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

                if ($file->isValid()) {
                    $file->storeAs('products/', $filename, 'public');
                    $product->update(['product_image' => $filename]);
                } else {
                    return back()->withErrors(['product_image' => 'Invalid image file']);
                }
            }

            return redirect()
                ->back()
                ->with('success', 'Product has been created with code: ' . $product->code);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    // Helper method to generate a unique product code
    private function generateUniqueCode()
    {
        do {
            $code = 'PC' . strtoupper(uniqid());
        } while (Product::where('code', $code)->exists());

        return $code;
    }

    public function show(Product $product)
    {
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        $query = Category::query();

        // Dynamic role-based filtering
        if (!$user->hasRole(['super-admin', 'admin'])) {
            $userRoles = $user->getRoleNames();
            $query->whereIn('role_name', $userRoles);
        }

        $categories = $query->get();

        // Fetch sub-categories for the product's category
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        return view('products.edit', [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'units' => Unit::all(),
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            // Update basic info, excluding quantity and image
            $product->update(array_merge(
                $request->only([
                    'code',
                    'name',
                    'category_id',
                    'sub_category_id',
                    'unit_id',
                    'buying_price',
                    'selling_price',
                    'quantity_alert',
                    'tax',
                    'tax_type',
                    'notes',
                ]),
                [
                    'slug' => Str::slug($request->get('name')),
                ]
            ));

            // If image is updated
            if ($request->hasFile('product_image')) {
                // Delete old image if exists
                if ($product->product_image) {
                    \Storage::disk('public')->delete('products/' . $product->product_image);
                }

                // Store new image
                $file = $request->file('product_image');
                $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

                if ($file->isValid()) {
                    $file->storeAs('products/', $fileName, 'public');
                    $product->update(['product_image' => $fileName]);
                } else {
                    return back()->withErrors(['product_image' => 'Invalid image file']);
                }
            }

            return redirect()
                ->route('products.index')
                ->with('success', 'Product has been updated!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Product $product)
    {
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            \Storage::disk('public')->delete('products/' . $product->product_image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }
}
