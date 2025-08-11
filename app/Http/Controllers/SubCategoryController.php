<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\SubCategory\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:view subcategory')->only(['index', 'show']);
        $this->middleware('permission:create subcategory')->only(['create', 'store']);
        $this->middleware('permission:update subcategory')->only(['edit', 'update']);
        $this->middleware('permission:delete subcategory')->only(['destroy']);
    }

    public function index()
    {
        $subCategories = SubCategory::with('category')->get();
        return view('sub_categories.index', [
            'subCategories' => $subCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('sub_categories.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        SubCategory::create($request->validated());

        return redirect()
            ->route('sub-categories.index')
            ->with('success', 'Sub-category has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        return view('sub_categories.show', [
            'subCategory' => $subCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('sub_categories.edit', [
            'subCategory' => $subCategory,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory->update($request->all());

        return redirect()
            ->route('sub-categories.index')
            ->with('success', 'Sub-category has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()
            ->route('sub-categories.index')
            ->with('success', 'Sub-category has been deleted!');
    }
}
