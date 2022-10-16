<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;


use GlobalPayments\Api\Services\PayFacService;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\ServiceConfigs\Gateways\PorticoConfig;
use GlobalPayments\Api\Entities\Enums\Environment;
// use GlobalPayments\Api\Services\PayFacService;


class SplitPayRequest extends AbstractHeartlandRequest
{


    protected function setServicesConfig()
    {
        $config = new PorticoConfig();
        $config->certificationStr = '5dbacb0fc504dd7bdc2eadeb7039dd';
        $config->terminalId = '7039dd';
        $config->environment = $this->getTestMode() ? Environment::TEST : Environment::PRODUCTION;
        $config->selfSignedCertLocation = \app_path('../test-hertland.crt');

        ServicesContainer::configureService($config);
    }


    public function runTrans()
    {

        $this->setGoodResponseCodes(array('00', '10'));

        $chargeMe = $this->gpCardObj;

        $amount = $this->getFeeAmount();
        
        return PayFacService::splitFunds()
        ->withAccountNumber($this->getWithAccountNumber())
        ->withReceivingAccountNumber($this->getWithReceivingAccountNumber())
        ->withAmount($amount * 100)
        ->withTransNum($this->getTransactionId())
        ->execute();
    }
}
