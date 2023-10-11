<?php

namespace App\Services\Payment\Providers;
use App\Services\Payment\Contracts\AbstractProviderInterface;
use App\Services\Payment\Contracts\PayableInterface;
use App\Services\Payment\Contracts\VerifyableInterface;

class IDPayProvider extends AbstractProviderInterface implements PayableInterface,VerifyableInterface
{
 public function pay(){

 }
 public function verify(){

 }
}