<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    public function getAddresses(Request $request)
    {
        $address=auth()->user()->myAddress()->get();
        return $address;
    }
    public function getProducts(Request $request)
    {
        $user = auth()->user();
        $user->load('productsByTypes.products.sizes','productsByTypes.products.images');

        return response()->json([$user->productsByTypes]);

    }
}
