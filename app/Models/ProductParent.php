<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductParent extends Model
{
    use HasFactory;
    protected $table = 'products_parent';
    protected $fillable = [
        'user_id',
        'product_id',
    ];
}
