<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    protected $table = 'exports';
    protected $primaryKey = 'export_id';
    public $incrementing = false;
    protected $keyType = 'string';


   protected $fillable = [
    'export_id',
    'customer_id',
    'account_id',
    'note',
    'total_amount',
    'export_date',
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

     public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id'); // account_id là khóa ngoại trong bảng exports, 'id' là khóa chính trong bảng accounts
    }

}

