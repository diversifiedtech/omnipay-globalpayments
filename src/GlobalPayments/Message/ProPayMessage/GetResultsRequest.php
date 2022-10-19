<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayHppResultResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;

class GetResultsRequest extends AbstractProPayRequest
{
    protected $endpoint;


    public const PROPAY_TEST = "https://xmltestapi.propay.com/";
    public const PROPAY_PRODUCTION = "https://xmlapi.propay.com/";

    public const PAYERS = "ProtectPay/Payers/";
    public const HPP = "ProtectPay/HostedTransactions/";
    public const RESULTS = "ProtectPay/HostedTransactionResults/";


    protected $payer_id;

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        $response = new ProPayHppResultResponse($this, $this->runTrans());
        $response->setPayerId($this->getPayerId());
        return $response;
    }


    protected function setServicesConfig()
    {
    }





    public function runTrans()
    {

        $path = self::RESULTS . $this->getHostedTransactionId();

dump($path);
        return $this->callEndpoint('GET',$path,[]);
        
    }


}
