<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayMerchantResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use Omnipay\GlobalPayments\Message\ProPaySplitResponse;
use Omnipay\GlobalPayments\Message\ProPayStoredResponse;

class CreateMerchantProfileRequest extends AbstractProPayRequest
{
    protected $endpoint;

    protected $certAuth = false;

    protected $payer_id;

    const CREATE_PROFILE_ID = "protectpay/MerchantProfiles/";

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        $response = new ProPayMerchantResponse($this, $this->runTrans());
        return $response;
    }


    protected function setServicesConfig()
    {
    }




    public function runTrans()
    {
        $data = [
            "ProfileName" => $this->getProfileName(),
            "PaymentProcessor" => "LegacyProPay",
            "ProcessorData" => [
                [
                    "ProcessorField" => "certStr",
                    "Value" => $this->getCertStr(),
                ],
                [
                    "ProcessorField" => "accountNum",
                    "Value" => $this->getWithAccountNumber(),
                ],
                [
                    "ProcessorField" => "termId",
                    "Value" => $this->getTermid(),
                ]
            ]
        ];



        return $this->callEndpoint('PUT', self::CREATE_PROFILE_ID, $data);

    }
}
