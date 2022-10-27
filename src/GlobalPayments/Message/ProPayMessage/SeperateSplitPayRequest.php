<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use Omnipay\GlobalPayments\Message\ProPaySplitResponse;

class SeperateSplitPayRequest extends AbstractProPayRequest
{
    protected $endpoint;

    protected $certAuth = true;

    // public const PROPAY_TEST = "https://xmltestapi.propay.com/";
    // public const PROPAY_PRODUCTION = "https://xmlapi.propay.com/";

    public const SPLIT_PAY = "propayapi/TimedPull";
    // public const HPP = "ProtectPay/HostedTransactions/";

    protected $payer_id;

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();
        $this->setGoodResponseCodes(array('00', '10'));


        $response = new ProPaySplitResponse($this, $this->runTrans());
        $response->setPayerId($this->payer_id);
        return $response;
    }


    protected function setServicesConfig()
    {
    }




    public function runTrans()
    {
        $data = [
            "accountNum" => $this->getWithAccountNumber(),
            "recAccntNum" => $this->getWithReceivingAccountNumber(),
            "amount" => (int) round($this->getAmount(), 2),
            "transNum" => $this->getTransactionId(),
        ];



        return $this->callEndpoint('PUT', self::SPLIT_PAY, $data);

    }
}
