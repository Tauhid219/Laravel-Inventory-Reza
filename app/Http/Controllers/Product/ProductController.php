<?php

namespace App\Http\Controllers\Product;

use App\Actions\Product\HandleProductImage;
use App\Actions\Product\UpsertProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view product')->only(['index', 'show']);
        $this->middleware('permission:create product')->only(['create', 'store']);
        $this->middleware('permission:update product')->only(['edit', 'update']);
        $this->middleware('permission:delete product')->only(['destroy']);
        $this->middleware('deny.demo')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        return view('products.index');
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $query = Category::select(['id', 'name']); // Only select necessary fields

        if (!$user->hasRole(['super-admin', 'admin', 'demo-admin'])) {
            $query->whereIn('role_name', $user->getRoleNames());
        }

        return view('products.create', [
            'categories' => $query->get(),
            'units' => Unit::select(['id', 'name'])->get() // Only select necessary fields
        ]);
    }

    public function getSubCategories($category_id)
    {
        $subCategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json(['subCategories' => $subCategories]);
    }

    public function store(StoreProductRequest $request, UpsertProduct $upsertProduct)
    {
        try {
            $product = $upsertProduct->create(
                array_merge($request->validated(), $request->only(['code', 'selling_price'])),
                $request->file('product_image'),
            );

            return redirect()
                ->back()
                ->with('success', 'Product has been created with code: ' . $product->code);
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function show(Product $product)
    {
        // Eager load relations to prevent N+1 queries in the view
        $product->load(['category', 'subCategory', 'unit']);

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
        if (!$user->hasRole(['super-admin', 'admin', 'demo-admin'])) {
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

    public function update(UpdateProductRequest $request, Product $product, UpsertProduct $upsertProduct)
    {
        try {
            $upsertProduct->update(
                $product,
                array_merge($request->validated(), $request->only(['code', 'selling_price'])),
                $request->file('product_image'),
            );

            return redirect()
                ->route('products.index')
                ->with('success', 'Product has been updated!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Product $product, HandleProductImage $handleProductImage)
    {
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            $handleProductImage->delete($product->product_image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }
}
