<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utilities\ImageUploader;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\products\StoreRequest;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\TryCatch;

class ProductsController extends Controller
{
    public function create(){
       $categories = Category::all();
        return view('admin.products.create',compact('categories'));
    }
    public function store(StoreRequest $request){
        $validation_data=$request->validated();
       $admin = User::where('email','admin@gmail.com')->first(); 
        $createdProducts= Product::create([
                'title' => $validation_data['title'],
                'category_id' => $validation_data['category_id'],
                'price'=>$validation_data['price'],      
                'owner_id'=>$admin->id,
                'description'=>$validation_data['description'],
      ]);

    try
    {
        $basePath = 'products/' . $createdProducts->id . '/';
        $sourceImageFullPath= $basePath . 'source_url_' . $validation_data['source_url']->getClientOriginalName();     
        $images = [
          'tumbnails_url' => $validation_data['tumbnail_url'],
          'demo_url' => $validation_data['demo_url'],
        ];
        $imagesPath=ImageUploader::uploadMany($images,$basePath);
        ImageUploader::upload($validation_data['source_url'],$sourceImageFullPath,'local_storage');
        $updatedProducts= $createdProducts -> update([
            'demo_url'=>$imagesPath['demo_url'],
            'source_url'=>$sourceImageFullPath ,
            'tumbnail_url'=>$imagesPath['tumbnails_url'],
        ]);
        
        if(!$updatedProducts){
            throw new \Exception('failed to uploaded products');
        }
        return back()->with('success','seccessfully  add image');
    }
    catch (\Exception $e) 
    {
        return back()->with('failed', $e->getMessage());
    }
    }
}
