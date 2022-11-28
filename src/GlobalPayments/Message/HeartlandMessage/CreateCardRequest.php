<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

use GlobalPayments\Api\PaymentMethods\CreditCardData;

class CreateCardRequest extends AbstractHeartlandRequest
{
    public function runTrans()
    {
        $this->setGoodResponseCodes(array('00', '85'));

        $chargeMe = $this->gpCardObj;

        $card_data = $this->getCard();
        if($card_data){

            $card = new CreditCardData();
            $card->number = $card_data->getNumber();
            $card->expMonth = $card_data->getExpiryMonth();
            $card->expYear = $card_data->getExpiryYear();
            $card->cvn = $card_data->getCvv();

            return $card->verify()
            ->withRequestMultiUseToken(true)
            ->withAddress($this->gpBillingAddyObj)
            //This doesnt work. IDK
            // ->withDescription($this->getDescription())
            ->withClientTransactionId($this->getTransactionId())
            ->execute();
        }

        return $chargeMe->verify()
            ->withRequestMultiUseToken(true)
            ->withAddress($this->gpBillingAddyObj)
            ->withDescription($this->getDescription())
            ->withClientTransactionId($this->getTransactionId())
            ->execute();
    }
}
