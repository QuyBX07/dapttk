<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name', 'category_id', 'description', 'unit', 'quantity', 'image', 'price'
    ];

    // Quan hệ với Category (mỗi sản phẩm thuộc về một thể loại)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Quan hệ với ImportDetail (mỗi sản phẩm có thể xuất hiện trong nhiều chi tiết nhập hàng)
    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class, 'product_id', 'id');
    }

    // Quan hệ với ExportDetail (mỗi sản phẩm có thể xuất hiện trong nhiều chi tiết xuất hàng)
    public function exportDetails()
    {
        return $this->hasMany(ExportDetail::class, 'product_id', 'id');
    }
}
