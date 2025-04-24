<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_id', 'customer_id', 'total_amount', 'export_date'
    ];

    // Quan hệ với Customer (mỗi xuất khẩu thuộc về một khách hàng)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Quan hệ với ExportDetail (mỗi xuất khẩu có thể có nhiều chi tiết xuất hàng)
    public function exportDetails()
    {
        return $this->hasMany(ExportDetail::class, 'export_id', 'export_id');
    }
}

