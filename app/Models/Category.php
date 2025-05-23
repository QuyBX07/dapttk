<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['category_id', 'name'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->category_id) {
                $model->category_id = (string) Str::uuid();
            }
        });
    }
}

