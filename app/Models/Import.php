<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Import extends Model
{
    use HasFactory;

    protected $primaryKey = 'import_id';
    public $incrementing = false; // Sử dụng UUID
    protected $keyType = 'string'; // UUID là kiểu string

    protected $fillable = [
        'import_id', 'supplier_id', 'total_amount','account_id','is_delete','note', 'import_date'
    ];

    // Quan hệ với supplier (mỗi nhập khẩu thuộc về một nhà cung cấp)
    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    // Quan hệ với ImportDetail (mỗi nhập khẩu có thể có nhiều chi tiết nhập hàng)
    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class, 'import_id', 'import_id');
    }

    // Quan hệ với Account (người tạo phiếu nhập)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
    protected static function booted(): void
    {
        static::creating(function ($import) {
            if (empty($import->import_id)) {
                $import->import_id = (string) Str::uuid();
            }
        });
    }
}
