<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' created successfully.");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory(
            $category,
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function destroy(Category $category)
    {
        $result = $this->categoryService->deleteCategory($category);

        if ($result['success']) {
            return redirect()->route('admin.categories.index')->with('success', $result['message']);
        }

        return redirect()->route('admin.categories.index')->with('error', $result['message']);
    }
}
