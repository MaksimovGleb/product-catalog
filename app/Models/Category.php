<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            if ($category->products()->withTrashed()->exists()) {
                throw new \Exception('Нельзя удалить категорию, в которой есть товары (включая удаленные в корзину).');
            }
        });
    }
}
