<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;

class ProPayHppResultResponse extends ProPayHppResponse
{

    const __SUCCESS_CODES = ['00', '10'];

    public function isSuccessful()
    {
        return parent::isSuccessful() && in_array($this->getCode(),static::__SUCCESS_CODES);
    }

    public function getHppId(){
        return $this->getDataItem('HostedTransactionIdentifier');
    }

    public function getMessage()
    {
        return $this->getDataItem('TransactionResultMessage');
    }

    public function getCode()
    {
        return $this->getDataItem('TransactionResult');
    }

    public  function getDataItem($key){
        if(!$this->response->HostedTransaction){
            return null;
        }
        return $this->response->HostedTransaction->$key ?? null;
    }

    public function getAuthorizationNumber()
    {
        return $this->getDataItem('authorization_num');
    }

    public function getTransactionTag()
    {
        return $this->getDataItem('TransactionId');
    }

    public function getTransactionReference()
    {
        return $this->getAuthorizationNumber() . '::' . $this->getTransactionTag();
    }

    public function getTransactionId()
    {
        return $this->getDataItem('TransactionId');
    }

    public function getCardReference()
    {
        return $this->getDataItem('transarmor_token');
    }

    public function getPayerId()
    {
        return $this->getDataItem('transarmor_token');
    }




    public function getAmount(){
        return $this->getDataItem('GrossAmt');
    }
}
