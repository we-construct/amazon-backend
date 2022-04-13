<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    //
    public function addAddress(Request $request){
        $rules = [
            'address' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return  response($errors, 419);
        } else {
            Address::create([
                'address' => $request->address,
                'user_id' => auth()->user()->id,
            ]);

            return response('success', 200);
        }

    }
}
