<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';
    public $incrementing = false; // vì bạn dùng UUID
    protected $keyType = 'string'; // UUID là kiểu string
    protected $fillable = [
        'customer_id', 'name', 'phone', 'email', 'address'
    ];

    // Quan hệ với Import (mỗi khách hàng có thể có nhiều nhập khẩu)
    public function imports()
    {
        return $this->hasMany(Import::class, 'customer_id', 'customer_id');
    }

    // Quan hệ với Export (mỗi khách hàng có thể có nhiều xuất khẩu)
    public function exports()
    {
        return $this->hasMany(Export::class, 'customer_id', 'customer_id');
    }
    protected static function booted(): void
    {
        static::creating(function ($customer) {
            if (empty($customer->customer_id)) {
                $customer->customer_id = (string) Str::uuid();
            }
        });
    }
}
