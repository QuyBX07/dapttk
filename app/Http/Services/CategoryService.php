<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function find(string $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function getAll()
    {
        return $this->categoryRepository->findAll();
    }

    public function paginate(int $perPage = 10)
    {
        return $this->categoryRepository->paginate($perPage);
    }

    public function search(string $query)
    {
        return $this->categoryRepository->search($query);
    }
}
