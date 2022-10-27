<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

class AchPurchaseRequest extends AbstractHeartlandRequest
{
    public function runTrans()
    {
        $this->setGoodResponseCodes(array('00'));

        $chargeMe = $this->gpCheckObj;

        $amount = $this->getAmount();
        $chargeMe->charge($amount)
        ->withAddress($this->gpBillingAddyObj)
        ->withCurrency($this->getCurrency())
        ->withDescription($this->getDescription())
        ->withClientTransactionId($this->getTransactionId())
        // ->withStoredCredential($this->gpStoredCredObj)
        ->execute();
    }
}
