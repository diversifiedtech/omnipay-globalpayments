<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Exception\RuntimeException;

class ProPayHppResponse extends CommonAbstractResponse
{
    protected $payer_id;

    const SUCCESS = "SUCCESS";

    public function __construct($request, $data)
    {
        $this->request = $request;

        $result = json_decode($data); 


        if(!$result && !$result->Result){
            throw new RuntimeException('Invalid Response From Gateway');
        }

        $this->response = $result;
    }

    public function isSuccessful()
    {
        return $this->response->Result->ResultValue == static::SUCCESS;
    }

    public function setPayerId($value){
        $this->payer_id = $value;
        return $this;
    }

    public function getPayerId(){
        return $this->payer_id;
    }

    public function getHppId(){
        return $this->response->HostedTransactionIdentifier ?? null;
    }

    public function getMessage()
    {
        return $this->response->Result->ResultMessage ?? null;
    }

    public function getCode(){
        return $this->response->Result->ResultCode ?? null;
    }

    public function getData(){

        if($this->response == null){
            return [];
        }
        $data = get_object_vars($this->response);
        $data["HostedTransaction"] = isset($data["HostedTransaction"]) ? get_object_vars($data["HostedTransaction"]) : null;
        $data["Result"] = isset($data["Result"]) ? get_object_vars($data["Result"]) : null;

        return $data;
    }
}
