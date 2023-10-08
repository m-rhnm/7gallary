<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utilities\ImageUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest as ProductsUpdateRequest;
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
       $admin = User::where('email','z.rahnemazadeh@gmail.com')->first(); 
        $createdProducts= Product::create([
                'title' => $validation_data['title'],
                'category_id' => $validation_data['category_id'],
                'price'=>$validation_data['price'],      
                'owner_id'=>$admin->id,
                'description'=>$validation_data['description'],
      ]);
      return $this->uploaedImages($createdProducts,$validation_data);

    }

    public function all(){
        $products = Product::paginate(4);

         return view('admin.products.all',compact('products'));
     }

     public function downloadDemo($product_id)
     {
        $product = Product::findOrFail($product_id);
        return response()->download(public_path($product->demo_url));
     }
     public function downloadSource($product_id)
     {
        $product = Product::findOrFail($product_id);
       // dd(storage_path('app\local_storage/'.$product->source_url));
       return response()->download(storage_path('app\local_storage/'.$product->source_url));
     }
     public function delete($product_id){
        $category = Product::findOrFail($product_id);
        $category->delete();
        return back()->with('success',"product deleted successfully");
    }
    
    public function edit($product_id){
        $categories = Category::all();
        $product = Product::findOrFail($product_id); 
      return view('admin.products.edit',compact('product', 'categories'));
    }
    
    public function update(ProductsUpdateRequest $request,$product_id){
        $validation_data=$request->validated();
        $product = Product::findOrFail($product_id);
        $updatedProduct= $product->update([
            'title' => $validation_data['title'],
            'category_id' => $validation_data['category_id'],
            'price'=>$validation_data['price'],      
            'description'=>$validation_data['description'],
        ]);
          return $this -> uploaedImages($product,$validation_data);
        
    }

    private function uploaedImages($createdProducts,$validation_data){
        try
    {
        $basePath = 'products/' . $createdProducts->id . '/';
        $sourceImageFullPath = null; 
        $images = [];
        $data = [];
        if(isset($validation_data[ 'source_url'])){
            $sourceImageFullPath= $basePath . 'source_url_' . $validation_data['source_url']->getClientOriginalName();  
            ImageUploader::upload($validation_data['source_url'],$sourceImageFullPath,'local_storage');
            $data +=['source_url'=>$sourceImageFullPath];
        }

        if(isset($validation_data['tumbnail_url'])){
            $images +=['tumbnails_url' => $validation_data['tumbnail_url']];
            $fullPath  = $basePath . 'tumbnail_url' . '_' .$validation_data['tumbnail_url']->getClientOriginalName();
            ImageUploader::upload($validation_data['tumbnail_url'],$fullPath, 'public_storage');
            $data +=[ 'tumbnail_url'=>$fullPath];
        }
        if(isset($validation_data['demo_url'])){
            $images +=['demo_url' => $validation_data['demo_url'],];
            $fullPath  = $basePath . 'demo_url' . '_' .$validation_data['tumbnail_url']->getClientOriginalName();
            ImageUploader::upload($validation_data['demo_url'],$fullPath, 'public_storage');
            $data +=[ 'demo_url'=> $fullPath ];

        }
 
        $updatedProducts= $createdProducts -> update($data);
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
