<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id', 'customer_id', 'total_amount', 'import_date'
    ];

    // Quan hệ với Customer (mỗi nhập khẩu thuộc về một khách hàng)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Quan hệ với ImportDetail (mỗi nhập khẩu có thể có nhiều chi tiết nhập hàng)
    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class, 'import_id', 'import_id');
    }
}
