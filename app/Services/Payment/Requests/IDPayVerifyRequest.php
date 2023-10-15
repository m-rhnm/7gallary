<?php

namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class IDPayVerifyRequest implements RequestInterface
{
    private $id;
    private $orderId;
    private $apiKey;


    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->orderId = $data['order_id'];
        $this->apiKey = $data['apiKey'];
    }
    public function getId()
    {
        return $this->id;
    }
    public function getOrderId()
    {
        return $this->orderId; 
    }
    public function getAPIKEY()
    {
        return $this->apiKey; 
    }
   

    
}