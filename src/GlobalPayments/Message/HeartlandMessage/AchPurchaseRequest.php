<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

class ACHPurchaseRequest extends AbstractHeartlandRequest
{
    public function runTrans()
    {
        $this->setGoodResponseCodes(array('00'));

        $chargeMe = $this->gpCheckObj;

        $amount = $this->getAmount();
        $feeAmount = $this->getParameter('feeAmount');
        $cm = null;

        if($feeAmount){
            $amount = number_format(round((float) $amount) + round((float) $feeAmount),2, '.', '');
            $cm = $chargeMe->charge($amount)
            ->withSurchargeAmount($feeAmount);
        }else{
            $cm = $chargeMe->charge($amount);
        }

        return $cm->withAddress($this->gpBillingAddyObj)
        ->withCurrency($this->getCurrency())
        ->withDescription($this->getDescription())
        ->withClientTransactionId($this->getTransactionId())
        // ->withStoredCredential($this->gpStoredCredObj)
        ->execute();
    }
}
