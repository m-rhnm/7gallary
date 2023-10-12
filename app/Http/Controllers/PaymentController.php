<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Payment\PayRequest;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Payment\Requests\IDPayRequest;

class PaymentController extends Controller
{

    public function pay(PayRequest $request)
    {
      $validatedData = $request->validated();
       $user = User::firstOrCreate([
        'email' => $validatedData['email'],
       ],[
        'name' => $validatedData['name'],
        'mobile' => $validatedData['mobile'],
       ]);

      try {
        $orderItems = json_decode(Cookie::get('basket'),true); 
       $product= Product::findMany(array_keys($orderItems));
        $productsPrice = $product->sum('price');
        $refCode = Str::random(30);
        $createdOrder = Order::create([
            'amount' =>  $productsPrice,
            'ref_code' => $refCode,
            'status' => 'unpaied',
            'user_id' => $user->id,
        ]);
        $orderItemsForCreateOrder=$product->map(function($product){
           $currentProduct= $product->only('price','id');
           $currentProduct['Product_id']=$currentProduct['id'];
           unset($currentProduct['id']);
           return $currentProduct;
        });
       
        $createdOrder->orderItems()->createMany(
            $orderItemsForCreateOrder->toArray());

            $refId = rand(1111,9999);
            $createdPayment=Payment::create([
                'getaway'=>'idpay',
                'ref_id' => $refId,
                'res_id' => $refId,
                'status' => 'unpaid',
                'order_id' => $createdOrder->id,
            ]);
     
        $idPayRequest = new IDPayRequest([
            'amount' =>$productsPrice , 
            'user' => $user->id,
            'order_id' => $refCode,

        ]);
    $paymentService = new PaymentService(PaymentService::IDPAY, $idPayRequest);
      return  $paymentService->pay(); 
    } catch (\Exception $e) {
        return back()->with('failed',$e->getMessage());
        }
   
    }
    public function calback()
    {
        
    }
}
