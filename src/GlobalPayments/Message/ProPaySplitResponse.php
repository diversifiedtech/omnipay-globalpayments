<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Exception\RuntimeException;

class ProPaySplitResponse extends CommonAbstractResponse
{
    protected $payer_id;

    const SUCCESS = "SUCCESS";

    public function __construct($request, $data)
    {
        $this->request = $request;

        $result = json_decode($data); 

        // TODO REMOVE
        dump($result);

        if(!$result || !$result->Status){
            throw new RuntimeException('Invalid Response From Gateway');
        }

        $this->response = $result;
    }

    public function isSuccessful()
    {
        return in_array(
            $this->response->Status, $this->request->getGoodReponseCodes()
        );
    }

    public function setPayerId($value){
        $this->payer_id = $value;
        return $this;
    }

    public function getPayerId(){
        return $this->payer_id;
    }

    public function getAccountNumber(){
        return $this->response->AccountNumber ?? null;
    }

    public function getTransactionNumber(){
        return $this->response->TransactionNumber ?? null;
    }

    public function getMessage()
    {
        return $this->response->Status ?? null;
    }

    public function getCode(){
        return $this->response->Status ?? null;
    }

    public function getData(){

        if($this->response == null){
            return [];
        }
        $data = $this->object_to_array($this->response);

        return $data;
    }

    protected function object_to_array($obj) {
        //only process if it's an object or array being passed to the function
        if(is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
            //recursively process EACH element regardless of type
                $item = $this->object_to_array($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }
}
