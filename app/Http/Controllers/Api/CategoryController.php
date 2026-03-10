<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $category = Category::create($request->only('name'));
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);
        $category->update($request->only('name'));
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->noContent();
    }
}
