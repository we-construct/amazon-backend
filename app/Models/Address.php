<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory;
    use HasFactory, Notifiable;
    public $table = "address";
    protected $fillable = [
        'user_id',
        'address',
    ];

    public function owner(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
