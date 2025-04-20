<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


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

    // Nếu có quan hệ với bảng imports
    public function imports()
    {
        return $this->hasMany(Import::class, 'supplier_id', 'supplier_id');
    }


}
