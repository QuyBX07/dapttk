<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name'
    ];

    // Quan hệ với Product (mỗi thể loại có nhiều sản phẩm)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
