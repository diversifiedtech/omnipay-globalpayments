<?php

namespace Omnipay\GlobalPayments\Message\ProPayMessage;

use Omnipay\GlobalPayments\Message\AbstractProPayRequest;
use Omnipay\GlobalPayments\Message\HeartlandMessage\AbstractHeartlandRequest;
use Omnipay\GlobalPayments\Message\ProPayHppResponse;
use Omnipay\GlobalPayments\Message\ProPayResponse;
use Omnipay\GlobalPayments\Message\ProPayStoredResponse;

class PurchaseStoredRequest extends AbstractProPayRequest
{
    protected $endpoint;


    // public const PROPAY_TEST = "https://xmltestapi.propay.com/";
    // public const PROPAY_PRODUCTION = "https://xmlapi.propay.com/";

    // public const PAYERS = "ProtectPay/Payers/";
    // public const HPP = "/protectpay/Payers/{PayerId}/PaymentMethods/ProcessedTransactions/";

    const MAX_COMMENT = 120 - 5;

    protected $payer_id;

    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        $response = new ProPayStoredResponse($this, $this->runTrans());
        $response->setPayerId($this->payer_id);
        return $response;
    }


    protected function setServicesConfig()
    {
    }




    public function runTrans()
    {

        $data = [
            "PaymentMethodID" =>  $this->getPaymentReference(),
            "IsRecurringPayment" =>  false,
            // "CreditCardOverrides" =>  {
            //     "FullName" =>  "Test User",
            //     "ExpirationDate" =>  "1014",
            //     "CVV" =>  "999",
            //     "Billing" =>  {
            //         "Address1" =>  "3400 N Ashton Blvd",
            //         "Address2" =>  "Suite 200",
            //         "Address3" =>  "",
            //         "City" =>  "Lehi",
            //         "State" =>  "UT",
            //         "ZipCode" =>  "84043",
            //         "Country" =>  "USA",
            //         "TelephoneNumber" =>  "8012223333",
            //         "Email" =>  "test@user.com"
            //     }
            // }
            // "MandateDateSigned" =>  "2013-03-11 12 => 46 => 31 PM",
            // "MandateId" =>  "11250653-9731-404a-a38c-bf3082d8d5e2",
            // "PayerOverrides" =>  {
            //     "IpAddress" =>  "127.0.0.1"
            // },
            "PayerAccountId" => $this->getPayerId(),
            "MerchantProfileId" => $this->getMerchantProfileId(),
            "Amount" => (int) round($this->getAmount(),2),
            "CurrencyCode" => $this->getCurrencyCode() ?? "USD",
            "Comment1" => $this->trimComment($this->getComment1()),

        ];

        $url = "/protectpay/Payers/" . $this->getPayerId() . "/PaymentMethods/ProcessedTransactions/";

        return $this->callEndpoint('PUT',$url,$data);
    }

    public function trimComment($comment1){
        return (strlen($comment1) >= static::MAX_COMMENT) ? substr($comment1,0,static::MAX_COMMENT) . '...' : $comment1;
    }


}
