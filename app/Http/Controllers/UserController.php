<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function getAddresses(Request $request)
    {
        $address=User::with('myAddress')->get()->pluck('myAddress');

        return $address;
    }
}
