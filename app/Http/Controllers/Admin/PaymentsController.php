<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentsController extends Controller
{
    public function all(){
   
        $payments = Payment::paginate(10); 
       return view('admin.payments.all',compact('payments'));
      }
}
