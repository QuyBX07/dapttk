<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Đảm bảo đã import Str

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'supplier_id';
    public $incrementing = false; // Vì dùng UUID
    protected $keyType = 'string'; // UUID là kiểu string

    protected $fillable = [
        'supplier_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    // Tự động tạo UUID khi tạo mới nhà cung cấp
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            // Tạo UUID v4 cho supplier_id
            $supplier->supplier_id = (string) Str::uuid();
        });
    }

    // Nếu có quan hệ với bảng imports
    public function imports()
    {
        return $this->hasMany(Import::class, 'supplier_id', 'supplier_id');
    }
}
