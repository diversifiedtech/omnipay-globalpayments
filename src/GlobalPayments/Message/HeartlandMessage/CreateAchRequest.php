<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

class CreateAchRequest extends AbstractHeartlandRequest
{
    public function runTrans()
    {
        $this->setGoodResponseCodes(array('00', '85'));

        $chargeMe = $this->gpCardObj;

        throw new \Exception("Error Processing Request", 1);
        

        return $chargeMe->verify()
            ->withRequestMultiUseToken(true)
            ->withAddress($this->gpBillingAddyObj)
            ->withDescription($this->getDescription())
            ->withClientTransactionId($this->getTransactionId())
            ->execute();
    }
}
