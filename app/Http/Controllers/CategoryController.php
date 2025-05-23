<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Services\CategoryService;


class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
{
    $categories = Category::paginate(10); // hoặc tùy số lượng bạn muốn phân trang
    return view('layout.category.content', compact('categories'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->categoryService->create($validated);

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }
public function update(Request $request, $category_id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $category = Category::where('category_id', $category_id)->firstOrFail();
    $category->name = $request->name;
    $category->save();

    return redirect()->back()->with('success', 'Cập nhật danh mục thành công!');
}


public function destroy($id)
{
    $category = Category::find($id);
    if ($category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    } else {
        return redirect()->route('categories.index')->with('error', 'Category not found.');
    }
}


public function search(Request $request)
{
    $query = $request->input('query');
    $categories = Category::where('name', 'like', "%$query%")->paginate(10);

    return view('layout.category.content', compact('categories'));
}
}

