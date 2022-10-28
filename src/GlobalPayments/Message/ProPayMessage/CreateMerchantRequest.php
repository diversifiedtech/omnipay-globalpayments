<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayMerchantOnboardingResponse;
use Omnipay\GlobalPayments\Message\ProPayMerchantResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use Omnipay\GlobalPayments\Message\ProPaySplitResponse;
use Omnipay\GlobalPayments\Message\ProPayStoredResponse;

class CreateMerchantRequest extends AbstractProPayRequest
{
    protected $endpoint;

    protected $certAuth = true;

    protected $payer_id;

    const CREATE_PROFILE_ID = "propayapi/signup";

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        $response = new ProPayMerchantOnboardingResponse($this, $this->runTrans());
        return $response;
    }


    protected function setServicesConfig()
    {
    }




    public function runTrans()
    {
        $data = $this->getMerchantOnboarding();


        return $this->callEndpoint('PUT', self::CREATE_PROFILE_ID, $data);

    }
}
