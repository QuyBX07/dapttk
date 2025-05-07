<?php
namespace App\Http\Repositories\Interfaces;
interface SearchRepositoryInterface {
    public function search(string $query);
}