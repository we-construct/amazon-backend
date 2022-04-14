<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'user_id' => Auth::user()->id,
            ]);

            return response('success', 200);
        }

    }

    public function updateAddress(Request $request, $id)
    {
        if (Address::where('id', $id)->exists()) {
            Address::where('id', $id)->update(['address' => $request->address]);
            return "success";
        } else {
            return response()->json("message: something went wrong");
        }
    }
    public function deleteAddress($id)
    {
        if (Address::where('id', $id)->exists()) {
            Address::find($id)->Delete();
            return "success";
        } else {
            return response()->json("message: something went wrong");
        }
    }
}
