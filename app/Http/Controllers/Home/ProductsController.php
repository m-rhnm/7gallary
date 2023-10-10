<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\App;

class ProductsController extends Controller
{

  public function index(Request $request)
    { 
        $products = null;
        if(isset($request->filter,$request->action))
         { $products = $this->findFilter($request?->filter,$request?->action)??Product::all();
        } elseif ($request->has('search')) {
          $products =  Product::where("title","LIKE","%{$request->input("search")}%")->get();
        }else {
          $products=Product::all();
        }
        $categories = Category::all();
        return view('frontend.products.all',compact('products','categories'));
    }
  public function show($product_id)
    {

        $products = Product::findOrFail($product_id); 
        $simillerProducts = Product::where('category_id',$products->category_id)->take(4)->get();
        return view('frontend.products.show',compact('products','simillerProducts'));
    }
  private function findFilter(string $className, string $methodName)
    {
        $baseNamespace = "App\Http\Controllers\Filters\\";
        $className= $baseNamespace  .(ucfirst($className).'Filter');
        if(!class_exists($className))
        {
          return null;
        }
        $object = new $className;
        
        if(!method_exists($object, $methodName))
        {
          return null;
        }
        return $object->{$methodName}();
        
    }
  }