<?php


namespace App\Services\Payment;

use App\Services\Payment\Exeptions\ProviderNotFoundExeption;
use App\Services\Payment\Contracts\RequestInterface;


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
    public function verify()
    {
        return $this->findProvider()->verify();
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

