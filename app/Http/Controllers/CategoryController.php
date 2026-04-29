<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Spatie\Permission\Models\Role;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view category')->only(['index', 'show']);
        $this->middleware('permission:create category')->only(['create', 'store']);
        $this->middleware('permission:update category')->only(['edit', 'update']);
        $this->middleware('permission:delete category')->only(['destroy']);
        $this->middleware('deny.demo')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $categories = Category::all();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $roles = Role::all(); // Get all roles
        return view('categories.create', compact('roles'));
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category has been created!');
    }

    public function show(Category $category)
    {
        return view('categories.show', [
            'category' => $category
        ]);
    }

    public function edit(Category $category)
    {
        $roles = Role::all();
        return view('categories.edit', [
            'category' => $category,
            'roles' => $roles
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category has been updated!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category has been deleted!');
    }
}
