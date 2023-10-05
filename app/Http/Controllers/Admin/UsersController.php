<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create(){
        return view('admin.users.create');
    }
    public function store(StoreRequest $request){
        $validation_data=$request->validated();
        $createdUser = User::create([
            'name' =>  $validation_data['name'],
            'email' =>  $validation_data['email'],
            'mobile' =>  $validation_data['mobile'],
            'role' =>  $validation_data['role'],
        
        ]);
        if(!$createdUser){
         return back()->with('failed','failed  to creating new user');
        }
        return back()->with('success',"Successfully created new user");
     }
    
    public function all(){
       $users = User::paginate(2);
         return view('admin.users.all',compact('users'));
    }
    public function delete($user_id){
        $deleteUser = User::findOrFail($user_id);
        $deleteUser->delete();
        return back()->with('success',"Successfully deleted user");
    }
    public function edit($user_id){
        $users = User::findOrFail($user_id);
        return view('admin.users.edit',compact('users'));

    }

    public function update(UpdateRequest $request,$user_id){
        $validation_data=$request->validated();
         $userUpdated=$user = User::find($user_id);
        $user->update([
            'name' =>  $validation_data['name'],
            'email' =>  $validation_data['email'],
            'mobile' =>  $validation_data['mobile'],
            'role' =>  $validation_data['role'],
        ]);
        if(!$userUpdated){
            return back()->with('failed','failed to update user');
           }
           return back()->with('success',"Successfully updated user");
        
    }


}
