<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

use GlobalPayments\Api\PaymentMethods\CreditCardData;

class PurchaseRequest extends AbstractHeartlandRequest
{
    public function runTrans()
    {
        $this->setGoodResponseCodes(array('00', '10'));

        $chargeMe = $this->gpCardObj;

        $amount = round(((int) $this->getAmount() / 100),2,2);

        $card_data = $this->getCard();
        if($card_data){

            $card = new CreditCardData();
            $card->number = $card_data->getNumber();
            $card->expMonth = $card_data->getExpiryMonth();
            $card->expYear = $card_data->getExpiryYear();
            $card->cvn = $card_data->getCvv();

            return $card->charge($amount)
            ->withAddress($this->gpBillingAddyObj)
            ->withCurrency($this->getCurrency())
            ->withDescription($this->getDescription())
            ->withClientTransactionId($this->getTransactionId())
            ->execute();
        }


        return $chargeMe->charge($amount)
        ->withAddress($this->gpBillingAddyObj)
        ->withCurrency($this->getCurrency())
        ->withDescription($this->getDescription())
        ->withClientTransactionId($this->getTransactionId())
        ->withStoredCredential($this->gpStoredCredObj)
        ->execute();

    }
}