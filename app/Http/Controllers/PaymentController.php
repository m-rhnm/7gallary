<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Payment\PayRequest;
use App\Mail\SendOrderedImages;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Payment\Requests\IDPayRequest;
use App\Services\Payment\Requests\IDPayVerifyRequest;
use Illuminate\Support\Facades\Mail;

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
            $createdPayment=Payment::create([
                'getaway'=>'idpay',
                'ref_code' =>$refCode,
                'status' => 'unpaid',
                'order_id' => $createdOrder->id,
            ]);
     
        $idPayRequest = new IDPayRequest([
            'amount' =>$productsPrice , 
            'user' => $user,
            'order_id' => $refCode,
            'apiKey' => config('services.geteways.id_pay.api_key'),

        ]);
    $paymentService = new PaymentService(PaymentService::IDPAY, $idPayRequest);
      return  $paymentService->pay(); 
    } catch (\Exception $e) {
        return back()->with('failed',$e->getMessage());
        }
    }
    public function callback(Request $request)
    {
       $paymentInfo =  $request->all();
      // dd($paymentInfo);
       $paymentId = $paymentInfo['id'];
       $paymentOrderId = $paymentInfo['order_id'];

       $idPayVerifyRequest =  new IDPayVerifyRequest([
            'id' => $paymentId ,
            'order_id' => $paymentOrderId,
            'apiKey' => config('services.geteways.id_pay.api_key'),
       ]); 
       $paymentService = new PaymentService(PaymentService::IDPAY, $idPayVerifyRequest);
       //return 
        $result = $paymentService->verify(); 
          if(!$result){
            return redirect()->route('home.checkout')->with('failed', 'payment failed');
          }
          if($request['status'] == 101){
            return redirect()->route('home.checkout')->with('failed', 'payment already done . check your email');
          }
        $currentPayment = Payment::where('ref_code' , $result['data']['order_id'])->first();
        $currentPayment ->update([
            'status' => 'paied',
            'res_id' => $result['data']['track_id'],
        ]);
        $currentOrder = $currentPayment->order;
        $currentOrder->update([
            'status' => 'paied',
        ]);
      $reservedImages = $currentOrder->orderItems->map(function($orderItems){
         return $orderItems->product->source_url;
      });
      $reservedImages->toArray();
      $currentUser = $currentOrder->user;
      Mail::to($currentUser)->send(new SendOrderedImages($reservedImages->toArray(),$currentUser));
      Cookie::queue('basket',null);
      return redirect()->route('home.products.all')->with('success', 
      'Your purchase has been successfully completed and the images have been sent to your e-mail address');
    }
}
