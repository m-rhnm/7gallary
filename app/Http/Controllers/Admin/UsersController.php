<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create(){
        return view('admin.users.create');
    }
    public function store(StoreRequest $request){
        $validation_data=$request->validated();
        $createdCategory = User::create([
        
        ]);
        if(!$createdCategory){
         return back()->with('failed','failed creating category');
        }
        return back()->with('success',"Successfully created category");
     }
    
    public function all(){
       $users = User::paginate(2);
         return view('admin.users.all',compact('users'));
    }


}
