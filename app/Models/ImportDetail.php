<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class ImportDetail extends Model
{
    use HasFactory;


    
    protected $primaryKey = 'importdetail_id';
    public $incrementing = false; // Sử dụng UUID
    protected $keyType = 'string'; // UUID là kiểu string
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
        return $this->belongsTo(Import::class, 'import_id', 'id');
    }


    
    protected static function booted(): void
    {
        static::creating(function ($importdetail) {
            if (empty($import->importdetail_id)) {
                $importdetail->importdetail_id = (string) Str::uuid();
            }
        });
    }
}
