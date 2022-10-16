<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;


use GlobalPayments\Api\Entities\Enums\Environment;
use GlobalPayments\Api\Entities\Enums\ServiceEndpoints;
use GlobalPayments\Api\ServiceConfigs\Gateways\PorticoConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Services\PayFacService;
use Omnipay\GlobalPayments\Message\HeartlandMessage\HeartlandResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use GlobalPayments\Api\Entities\Enums\AccountType;
use GlobalPayments\Api\Entities\Enums\CheckType;

class ProtectPayRequest extends AbstractHeartlandRequest
{


    const PROTECTPAY_PRODUCTION = "https://protectpay.propay.com/pmi/spr.aspx";
    const PROTECTPAY_TEST = "https://protectpaytest.propay.com/pmi/spr.aspx"; 
    protected $endpoint = "https://protectpay.propay.com/pmi/spr.aspx";

    protected $headers = [
        'Content-Type' => "application/xml"
    ];

    protected $xmlClass = "partner";
    protected $xmlCert = "";

    // protected $trans_type = "36";

    protected function setServicesConfig()
    {

        $this->endpoint = $this->getTestMode() ? self::PROTECTPAY_TEST : self::PROTECTPAY_PRODUCTION;
        $this->termid = $this->getTerminalId();
        $config = new PorticoConfig();
        $config->certificationStr = '5dbacb0fc504dd7bdc2eadeb7039dd';
        $config->terminalId = '7039dd';
        $config->environment = $this->getTestMode() ? Environment::TEST : Environment::PRODUCTION;
        $config->selfSignedCertLocation = \app_path('../test-hertland.crt');

        ServicesContainer::configureService($config);
    }

    public function creditcard_non(){
        $xmlString = "<?xml version='1.0'?>";
        $xmlString .= "<!DOCTYPE Request.dtd>";
        $xmlString .= "<CardHolderName>Nick Caruso</CardHolderName>";
        $xmlString .= "<XMLRequest>";
        $xmlString .= "<CardHolderName>Nick Caruso</CardHolderName>";
        $xmlString .= "<PaymentTypeId>Visa</PaymentTypeId>";
        $xmlString .= "<CardNumber>4111111111111111</CardNumber>";
        $xmlString .= "<ExpMonth>12</ExpMonth>";
        $xmlString .= "<ExpYear>29</ExpYear>";
        $xmlString .= "<CVV>411</CVV>";
        $xmlString .= "</XMLRequest>";


        $this->doit($xmlString);

    }


    public function runTrans()
    {

        throw new \Exception("Protect Pay is not setup");

        return $this->creditcard_non();

            //         'transactionId' => $extra->get('transactionId', CommonHelper::nuveiUniqueId()),
            // 'description' => $extra->get('description',"ACH Bill Payment"),
            // 'amount' => $amount->services_amt,
            // 'feeAmount' => $amount->fee_amt,
            // 'currency' => $extra->get('currency',"USD"),  
            // 'clientIp' => "127.0.0.1",
            // 'check' => new OmnipayEcheck([
            //     // 'checkHolderName' => $payment_info['firstName'] ?? null . " " . $payment_info['lastName'] ?? null,
            //     'accountType' => $extra->get('accountType',AccountType::CHECKING),                
            //     'checkType' => $extra->get('checkType',CheckType::PERSONAL),
            //     'secCode' => $extra->get('secCode',SecCode::WEB),
            //     'firstName' => $payment_info['firstName'] ?? null,
            //     'lastName' => $payment_info['lastName'] ?? null,
            //     'accountNumber' => (string) $payment_info['accountNumber'],
            //     'routingNumber' => (string) $payment_info['routingNumber'],
            //     'checkNumber' => $payment_info['checkNumber'] ?? null,
            //     'email' => $payment_info['email'] ?? null,
            //     'address1' => $payment_info['billingAddress1'] ?? null,
            //     'city' => $payment_info['billingCity'] ?? "",
            //     'state' => $payment_info['billingState'] ?? null,
            //     'postcode' => $payment_info['billingPostcode'] ?? null,
            //     'country' => $this->parseCountry($payment_info),
            //     'license' => $payment_info['license'] ?? null, //'12345678'
            // ]),


        $amount = $this->getAmount() * 100;
        $this->getTransactionId();

        $omnipayCheckObj = $this->getCheck();
        
        $accountTypeString = $omnipayCheckObj->getAccountType() == AccountType::SAVINGS ? "Savings" : "Checking";
        $checkTypeString = $omnipayCheckObj->getCheckType()  == CheckType::BUSINESS ? "Personal Account" : "Business Account";




        $xmlString = "<?xml version='1.0'?>";
        $xmlString .= "<!DOCTYPE Request.dtd>";
        $xmlString .= "<XMLRequest>";
        $xmlString .= "<certStr>My certStr</certStr>";
        $xmlString .= "<termid>termid</termid>";
        $xmlString .= "<class>partner</class>";
        $xmlString .= "<XMLTrans>";
        $xmlString .= "<transType>{$this->trans_type}</transType>";
        $xmlString .= "<amount>{$amount}</amount>";
        $xmlString .= "<accountNum>{$omnipayCheckObj->getAccountNumber()}</accountNum>";
        $xmlString .= "<RoutingNumber>{$omnipayCheckObj->getRoutingNumber()}</RoutingNumber>";
        $xmlString .= "<AccountNumber>123456</AccountNumber>";
        $xmlString .= "<accountType>{$accountTypeString}</accountType>";
        $xmlString .= "<StandardEntryClassCode>{$omnipayCheckObj->getSecCode()}</StandardEntryClassCode>";
        $xmlString .= "<accountName>{$checkTypeString}</accountName>";
        $xmlString .= "<invNum>{$this->getTransactionId()}</invNum>";
        $xmlString .= "</XMLTrans>";
        $xmlString .= "</XMLRequest>";

        $this->setGoodResponseCodes(array('00', '10'));



        return $this->doit($xmlString);

    }


    public function doit($data){

        $headers  = $this->headers;
        $endpoint = $this->endpoint;

        try{

            $httpResponse = $this->httpClient->request(
                "POST",
                $endpoint,
                $headers,
                $data
            );
        }catch(\Throwable $e){
        }

        return new ProPayResponse($this,$httpResponse->getBody()->getContents());
    }
}
