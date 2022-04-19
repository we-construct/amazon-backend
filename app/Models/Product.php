<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'description',
        'brand',
        'category',
        'color',
        'price',
        'product_type_id'
    ];

    public function sizes() {
        return $this->hasMany(Size::class,'product_id','id');
    }
    public function images() {
        return $this->hasMany(Image::class,'product_id','id');
    }

    public function getProductSizesAttribute() {
        return $this->sizes();
    }
}
