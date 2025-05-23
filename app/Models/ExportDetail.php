<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportDetail extends Model
{
    use HasFactory;
protected $fillable = [
    'exportdetail_id',
    'export_id',
    'product_id',
    'quantity',
    'price',
];
    // Quan hệ với Product (mỗi chi tiết xuất hàng có một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Quan hệ với Export (mỗi chi tiết xuất hàng thuộc về một xuất khẩu)
    public function export()
    {
        return $this->belongsTo(Export::class, 'export_id', 'export_id');
    }
}

