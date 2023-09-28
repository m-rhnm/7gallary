<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create(){
        return view('admin.categories.create');
    }
    public function store(StoreRequest $request){
        $validation_data=$request->validated();
       $createdCategory = Category::create([
        'title' => $validation_data['title'],
        'slug' =>$validation_data['slug'],
       ]);
       if(!$createdCategory){
        return back()->with('failed','failed creating category');
       }
       return back()->with('success',"Successfully created category");
    }
    public function all(){

        $categories =  Category::paginate(10);
       
        return view('admin.categories.all',compact('categories'));
    }
    public function delete($category_id){
        $category = Category::find($category_id);
        $category->delete();
        return back()->with('success','category deleted successfully');
    }

    public function edit($category_id){
        $category = Category::find($category_id); 
      return view('admin.categories.edit',compact('category'));
    }

    public function update(UpdateRequest $request,$category_id){
        $validation_data=$request->validated();
         $categoryUpdated=$category = Category::find($category_id);
        $category->update([
            'title'=>$validation_data['title'],
            'slug'=>$validation_data['slug'],
        ]);
        if(!$categoryUpdated){
            return back()->with('failed','failed to update category');
           }
           return back()->with('success',"Successfully updated category");
        
    }
}   
