<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    public $minutes =600;
    public function addToBasket($product_id){
        $product = Product::findOrFail($product_id);
        $basket = json_decode(Cookie::get('basket'),true);
        if(!$basket)
        {
            $basket =[
                $product->id=>[
                    'title' =>$product->title,
                    'price' =>$product->price,
                    'demo_url' =>$product->demo_url,
                ],
            ];
        $basket = json_encode($basket);
        Cookie::queue('basket',$basket,$this->minutes);
        return back()->with('success',' succesfully product add to basket');
        }
        if(isset($basket[$product->id])){
            return back()->with('success',' succesfully product add to basket');
        }
        $basket[$product->id]=[
                'title' =>$product->title,
                'price' =>$product->price,
                'demo_url' =>$product->demo_url,
            ];
        Cookie::queue('basket', json_encode($basket),$this->minutes);
        return back()->with('success',' succesfully product add to basket');
    }

    public function removeFromBasket($product_id){
        $basket = json_decode(Cookie::get('basket'),true);
        if(isset($basket[$product_id])){
            unset($basket[$product_id]);
        }
        Cookie::queue('basket', json_encode($basket),$this->minutes);
        return back()->with('success',' remove product from basket');
    }
}
