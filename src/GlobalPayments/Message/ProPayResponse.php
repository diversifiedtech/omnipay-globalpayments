<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Exception\RuntimeException;

class ProPayResponse extends CommonAbstractResponse
{
    public function __construct($request, $data)
    {
        $this->request = $request;
        try{
            $response = json_decode(json_encode(
                simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA)
            ),TRUE);

            if(!isset($response['XMLTrans'])){
                throw new RuntimeException('Invalid Response From Gateway');
            }

            $this->response = $response['XMLTrans'];

        }catch(\ErrorException $e){
            throw new RuntimeException('Invalid Response From Gateway');
        }

    }

    public function isSuccessful()
    {
        return isset($this->response["status"]) && 
        in_array(
            $this->response["status"], $this->request->getGoodReponseCodes()
        );
    }


    public function getMessage()
    {
        return $this->response->responseMessage  ?? null;
    }

    public function getCode()
    {
        return $this->response["status"]  ?? null;
    }

    public function getTransactionReference()
    {
        return $this->response["transNum"] ?? null;
    }

    public function getCardReference()
    {
        return null;
    }

    public function getAmount(){
        return $this->response["GrossAmt"] ?? null;
    }

    public function getCustomerReference()
    {
        return $this->response["transNum"] ?? null;
    }

    public function getPaymentMethodReference()
    {
        return null;
    }

    public function getInvoiceNumber()
    {
        return $this->response["invNum"] ?? null;
    }

    public function getData()
    {
        if($this->response == null){
            return [];
        }
        $data = $this->response;

        //Add extra parameters to match the regular response.
        $data['transactionReference']['transactionId'] = $this->getTransactionReference();
        $data['transactionReference']['authCode'] = $this->getTransactionReference();
        $data['message'] = $this->getMessage();
        $data['code'] = $this->getCode();
        $data['token'] = $this->getToken();
        return $data;
    }

    public function getToken(){
        return $this->response->token ?? null;
    }
}
