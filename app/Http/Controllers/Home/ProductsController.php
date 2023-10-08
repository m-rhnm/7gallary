<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class ProductsController extends Controller
{

    public function index(){
        $products = Product::all();
        $categories = Category::all();
        return view('frontend.products.all',compact('products','categories'));
       }
  public function show($product_id){

        $products = Product::findOrFail($product_id); 
        $simillerProducts = Product::where('category_id',$products->category_id)->take(4)->get();
        return view('frontend.products.show',compact('products','simillerProducts'));
  }
}
