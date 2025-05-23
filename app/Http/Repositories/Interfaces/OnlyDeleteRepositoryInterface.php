<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
interface OnlyDeleteRepositoryInterface
{
    public function getDeleted();
}