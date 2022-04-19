<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductParent;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addProduct(Request $request) {
        $productParent = ProductParent::create([
            'user_id' =>Auth::user()->id
        ]);
        $productParent->save();
        $data = $request->all();
        for($requestIndex = 0; $requestIndex <= count($data)-1; $requestIndex++) {

            $validator = Validator::make($data[$requestIndex], [
                    $data[$requestIndex]['name'] => 'required',
                    $data[$requestIndex]['description'] => 'required',
                    $data[$requestIndex]['brand'] => 'required',
                    $data[$requestIndex]['color'] => 'required',
                    $data[$requestIndex]['images'] => 'required',
                    $data[$requestIndex]['sizes'] => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => 'wrong params']);
            }
            $product = Product:: create([
                'name' => $data[$requestIndex]['name'],
                'description' => $data[$requestIndex]['description'],
                'brand' => $data[$requestIndex]['brand'],
                'category' => $data[$requestIndex]['category'],
                'color' => $data[$requestIndex]['color'],
                'price' => $data[$requestIndex]['price'],
                'product_type_id' => $productParent->id,
            ]);
            $product->save();
            for ($i = 0; $i <= count($data[$requestIndex]['sizes'])-1; $i++) {
                Size::create([
                    'size' => $data[$requestIndex]['sizes'][$i],
                    'product_id' => $product->id,
                ]);
            }
            for ($i = 0; $i <= count($data[$requestIndex]['images'])-1; $i++) {
                $imageName = time();
                $name = $data[$requestIndex]['images'][$i]->getClientOriginalName();
                $data[$requestIndex]['images'][$i]->move(public_path('images/' . $imageName), $name);
                Image::create([
                    'product_id' => $product->id,
                    "image" => $imageName . "/" . $name
                ]);
            }
        }
        return response()->json(['message' => 'data saved successfully']);
   }
   public function getProduct(Request $request) {
        $product = ProductParent::where('id',$request->parentId)->with('products.sizes','products.images')->get();
       return response()->json($product[0]);
   }
}
