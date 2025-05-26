<?php

namespace App\Http\Repositories\Eloquent;

use App\Models\Category;
use App\Http\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(string $id, array $data)
    {
        $category = Category::where('category_id', $id)->firstOrFail();
        return $category->update($data);
    }

    public function delete(string $id)
    {
        return Category::destroy($id);
    }

    public function find(string $id)
    {
        return Category::findOrFail($id);
    }

    public function findAll()
    {
        return Category::all();
    }

    public function search(string $query)
    {
        return Category::where('name', 'like', "%$query%")->get();
    }

    public function paginate(int $perPage = 10)
    {
        return Category::paginate($perPage);
    }
}
