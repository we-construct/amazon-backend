<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'user_id',
        'address',
    ];

}
