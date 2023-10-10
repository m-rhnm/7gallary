<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderbyFilter 
{
    public function newest(){
        return Product::orderBy('created_at','desc')->get();
    }
    public function cheapest(){
        return Product::orderBy('price','asc')->get();
    }
    public function expensive(){
        return Product::orderBy('price','desc')->get();
    }
    public function default(){
        return Product::all();
    }
    public function popular(){
        return Product::all();
    }


}
