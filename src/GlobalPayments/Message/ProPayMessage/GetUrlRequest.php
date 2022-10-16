<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;

class GetUrlRequest extends AbstractProPayRequest
{
    protected $endpoint;


    public const PROPAY_TEST = "https://xmltestapi.propay.com/";
    public const PROPAY_PRODUCTION = "https://xmlapi.propay.com/";

    public const PAYERS = "ProtectPay/Payers/";
    public const HPP = "ProtectPay/HostedTransactions/";

    protected $payer_id;

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        $response = new ProPayHppResponse($this, $this->runTrans());
        $response->setPayerId($this->payer_id);
        return $response;
    }


    protected function setServicesConfig()
    {
    }





    public function runTrans()
    {

        $payer_data = [ "Name" => $this->getName()];


        $result = $this->callEndpoint('PUT',self::PAYERS,$payer_data);
        $result = json_decode($result);
        if(!($result && $result->RequestResult && $result->RequestResult->ResultValue == "SUCCESS")){
            throw new \Exception("Could not create PayerId", 1);
        }

        $this->payer_id = $result->ExternalAccountID;


        $data = [
            "Amount" => $this->getAmount(),
            "PayerId" => $this->payer_id,
            "MerchantProfileId" => $this->getMerchantProfileId(),
            "AcceptMasterPass" => $this->getAcceptMasterPass() ?? false,
            "AvsRequirementType" => $this->getAvsRequirementType() ?? 3,
            "CardHolderNameRequirementType" => $this->getCardHolderNameRequirementType() ?? 1,
            "Comment1" => $this->getComment1(),
            "CssUrl" => $this->getCssUrl(),
            "CurrencyCode" => $this->getCurrencyCode() ?? "USD",
            "InvoiceNumber" => $this->getInvoiceNumber(),
            "OnlyStoreCardOnSuccessfulProcess" => $this->getOnlyStoreCardOnSuccessfulProcess() ?? true,
            "PaymentTypeId" => $this->getPaymentTypeId(),
            "ProcessCard" => $this->getProcessCard(),
            "Protected" => $this->getProtected(),
            "ReturnURL" => $this->getReturnURL(),
            "SecurityCodeRequirementType" => $this->getSecurityCodeRequirementType(),
            "StoreCard" => $this->getStoreCard(),
        ];

        return  $this->callEndpoint('PUT',self::HPP,$data);
    }


}
