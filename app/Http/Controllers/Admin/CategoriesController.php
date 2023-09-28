<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
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

}
