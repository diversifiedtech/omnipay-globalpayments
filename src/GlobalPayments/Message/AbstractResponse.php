<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;

abstract class AbstractResponse extends CommonAbstractResponse
{
    public function __construct($request, $data)
    {
        $this->request = $request;
        $this->response = $data;
    }

    abstract public function isSuccessful();

    public function getMessage()
    {
        return $this->response->responseMessage  ?? null;
    }

    public function getCode()
    {
        return $this->response->responseCode  ?? null;
    }

    public function getTransactionReference()
    {
        return $this->response->transactionId  ?? null;
    }

    public function getCardReference()
    {
        return $this->response->token  ?? null;
    }

    public function getCustomerReference()
    {
        return $this->response->id ?? null;
    }

    public function getPaymentMethodReference()
    {
        return $this->response->id  ?? null;
    }

    public function getData()
    {
        if($this->response == null){
            return [];
        }
        $data = get_object_vars($this->response);
        $data["transactionReference"] = isset($data["transactionReference"]) ? get_object_vars($data["transactionReference"]) : null;
        return $data;
    }

    public function getToken(){
        return $this->response->token;
    }


    public function getAuthorizationCode()
    {
        if($this->response->authorizationCode == null){
            return $this->response->authorizationCode;
        }
        return $this->response->authorizationCode;
    }
    public function getClientTransactionId()
    {
        if($this->response->clientTransactionId == null){
            return $this->response->clientTransactionId;
        }
        return $this->response->clientTransactionId;
    }
    public function getOrderId()
    {
        if($this->response->orderId == null){
            return $this->response->orderId;
        }
        return $this->response->orderId;
    }
    public function getPaymentMethodType()
    {
        if($this->response->paymentMethodType == null){
            return $this->response->paymentMethodType;
        }
        return $this->response->paymentMethodType;
    }
    public function getAlternativePaymentResponse()
    {
        if($this->response->alternativePaymentResponse == null){
            return $this->response->alternativePaymentResponse;
        }
        return $this->response->alternativePaymentResponse;
    }
}
