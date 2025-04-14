<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // dùng nếu muốn đăng nhập
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Account extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    protected $table = 'accounts'; // tên bảng

    protected $primaryKey = 'id'; // khóa chính

    public $incrementing = false; // vì dùng UUID

    protected $keyType = 'string'; // UUID là string

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
