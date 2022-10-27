<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use Omnipay\GlobalPayments\Message\ProPaySplitResponse;
use Omnipay\GlobalPayments\Message\ProPayXmlResponse;

class SeperateReverseSplitPayRequest extends AbstractProPayRequest
{

    const PROPAY_TEST = "https://xmltest.propay.com/API/PropayAPI.aspx";

    const PROPAY_PRODUCTION = "https://epay.propay.com/API/PropayAPI.aspx";

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


        $response = new ProPayXmlResponse($this, $this->runTrans());
        $response->setPayerId($this->payer_id);
        return $response;
    }


    protected function setServicesConfig()
    {
    }






    public function runTrans()
    {

        $data = "<?xml version='1.0'?>";
        $data .= "<!DOCTYPE Request.dtd>";
        $data .= "<XMLRequest>";
        $data .= "<certStr>" . $this->getCertStr() . "</certStr>";
        $data .= "<termid>" . $this->getTermid() . "</termid>";
        $data .= "<class>partner</class>";
        $data .= "<XMLTrans>";
        $data .= "<transType>43</transType>";
        $data .= "<accountNum>" . $this->getWithAccountNumber() . "</accountNum>";
        $data .= "<transNum>" . $this->getTransactionId() .  "</transNum>";
        $data .= "<amount>" . (int) round($this->getAmount(), 2) . "</amount>";
        $data .= "<ccAmount>0</ccAmount>";
        $data .= "<requireCCRefund>N</requireCCRefund>";
        $data .= "</XMLTrans>";
        $data .= "</XMLRequest>";

        return $this->callXmlEndpoint('POST', "", $data);

    }
}
