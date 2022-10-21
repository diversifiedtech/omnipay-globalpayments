<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;

class SeperateSplitPayRequest extends AbstractProPayRequest
{
    protected $endpoint;


    // public const PROPAY_TEST = "https://xmltestapi.propay.com/";
    // public const PROPAY_PRODUCTION = "https://xmlapi.propay.com/";

    public const SPLIT_PAY = "TimedPull/";
    // public const HPP = "ProtectPay/HostedTransactions/";

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
        $data = [

            "accountNum" => $this->getWithAccountNumber(),
            "recAccntNum" => $this->getWithReceivingAccountNumber(),
            "amount" => (int) round($this->getAmount(), 2),
            "transNum" => $this->getTransactionId(),
        ];

        $data = '{"accountNum":718553325,"recAccntNum":718554415,"amount":100,"transNum":13,"InvoiceNumber":"invoice number","comment1":"comment 1","comment2":"comment 2"}';


        $headers  = array_merge($this->headers, [
            'Content-Length' =>  strlen($data),
            'Authorization' => 'Basic ' . base64_encode($this->authHeader),
        ]);


        dump("PUT");
        dump("https://xmltestapi.propay.com/TimedPull/");
        dump($headers);
        dump($data);

        $httpResponse = $this->httpClient->request(
            "PUT",
            "https://xmltestapi.propay.com/propayAPI/TimedPull/",
            $headers,
            $data
        );
        dd($httpResponse->getBody()->getContents());
        return  $this->callEndpoint('PUT', self::SPLIT_PAY, $data);
    }
}
