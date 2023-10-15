<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PayableInterface;
use App\Services\Payment\Contracts\RequestInterface;
//use App\Services\Payment\Contracts\VerifyableInterface;
use App\Services\Payment\Contracts\AbstractProviderInterface;

interface VerifyableInterface 
{
      public function verify();  
}
class IDPayProvider extends AbstractProviderInterface implements PayableInterface,VerifyableInterface 
{
       public function __construct( public RequestInterface $request)
       {
       }
       public function pay()

       {
              $params = array(
              'order_id' => $this->request->getOrderId(),
              'amount' => $this->request->getAmount(),
              'name' =>  $this->request->getUser()->name,
              'phone' => $this->request->getUser()->mobile,
              'mail' => $this->request->getUser()->email,
              'callback' => route('payment.callback'),
              );

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'X-API-KEY: '. $this->request->getAPIKEY() .'',
              'X-SANDBOX: 1'
              ));
              $result = curl_exec($ch);
              curl_close($ch);

              $result = json_decode($result, true);
       
              if(isset($result['error_code'])){
                     throw new \InvalidArgumentException($result['error_message']);
              }
            
           return redirect()->to($result['link'])->send();
       }
       public function verify()
       {
              
              $params = array(
              'id' => $this->request->getId(),
              'order_id' => $this->request->getOrderId(),
              );
                   
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/inquiry');
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'X-API-KEY: '. $this->request->getAPIKEY() .'',
              'X-SANDBOX: 1',
              ));
                   
              $result = curl_exec($ch);
              $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              curl_close($ch);
                   
              //dd($httpcode);
            // dd($result = json_decode($result,true));
            $result = json_decode($result,true);
           // dd($result);

              if ($result['status'] == 10){
                     return 
                      [
                           'status' => true,
                           'status_code' => $result['status'],
                           'data' => $result
                      ];
              }  
              return false;
       }
}