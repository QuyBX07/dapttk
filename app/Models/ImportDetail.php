<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'importdetail_id', 'product_id', 'import_id', 'quantity', 'price'
    ];

    // Quan hệ với Product (mỗi chi tiết nhập hàng có một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Quan hệ với Import (mỗi chi tiết nhập hàng thuộc về một nhập khẩu)
    public function import()
    {
        return $this->belongsTo(Import::class, 'import_id', 'product_id');
    }
}
