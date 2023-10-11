<?php


namespace App\Services\Payment;

use App\Services\Payment\Exeptions\ProviderNotFoundExeption;
use App\Services\Payment\Providers\IDPayProvider;
use App\Services\Payment\Contracts\RequestInterface;
use App\Services\Payment\Providers\ZarinpalProvider;
use IDPayRequest;

class PaymentService
{
    public const IDPAY = "IDPayProvider";
    public const ZARINPAL = "ZarinpalProvider";

 
    public function __construct(private string $providerName,
    private RequestInterface $request)
    {
    }
    public function pay()
    {
        $this->findProvider()->pay();
    }
    private function findProvider()
    {
        $className= 'App\\Services\\Payment\\Providers\\' .
         $this->providerName;
         if(!class_exists($className)){
            throw new ProviderNotFoundExeption('درگاه پرداخت مورد نظر یافت نشد ');
         }
         return new $className($this->request);
    }
}
$idPayRequest = new IDPayRequest([
    'amount' =>1000 , 
    'user' => $user
]);
