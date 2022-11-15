<?php

namespace Omnipay\GlobalPayments\Message;

use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\StoredCredentialInitiator;
use GlobalPayments\Api\Entities\StoredCredential;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use Omnipay\GlobalPayments\CreditCard;
use Omnipay\GlobalPayments\Message\ProPayParametersTrait;
use Omnipay\GlobalPayments\Message\ProPayResponse;

abstract class AbstractProPayRequest extends \Omnipay\Common\Message\AbstractRequest
{

    use ProPayParametersTrait;

    protected $certAuth = false;


    const PROPAY_TEST = "https://xmltestapi.propay.com/";
    const PROPAY_PRODUCTION = "https://api.propay.com/";

    protected $gpBillingAddyObj;
    protected $gpCardObj;
    protected $gpStoredCredObj;

    protected $authHeader;

    protected $headers = [
        'Content-Type' =>  'application/json',
    ];


    protected abstract function runTrans();


    protected function setupAuth(){
        $this->authHeader = $this->certAuth ? $this->getCertAuthHeader() : $this->getAuthHeader();
        $this->endpoint = $this->getTestMode() ? static::PROPAY_TEST : static::PROPAY_PRODUCTION;
    }


    protected abstract function setServicesConfig();

    public function callXmlEndpoint($method = "POST", $path, $data){

        $endpoint = $this->endpoint . ltrim($path,"/");

        $httpResponse = $this->httpClient->request(
            $method,
            $endpoint,
            ['Content-Type' =>  'text/xml'],
            $data
        );        

        return $httpResponse->getBody()->getContents();

    }


    public function callEndpoint($method = "POST", $path, $data){
        $data = json_encode($data);

        $headers  = array_merge($this->headers,[
            'Content-Length' =>  strlen($data),
            'Authorization' => 'Basic ' . base64_encode($this->authHeader)
        ]);

        $endpoint = $this->endpoint . ltrim($path,"/");

        if($method == "GET"){
            $httpResponse = $this->httpClient->request(
                $method,
                $endpoint,
                $headers
            );
        }else{
            $httpResponse = $this->httpClient->request(
                $method,
                $endpoint,
                $headers,
                $data
            );
        }

        return $httpResponse->getBody()->getContents();
    }

    /** When send() is call this is called second after getData() */
    public function sendData($data)
    {
        $this->setupAuth();
        $this->setServicesConfig();

        return new ProPayResponse($this, $this->runTrans());
    }


    /**
     * Overrides parent class method to create a Omnipay\GlobalPayments\CreditCard.
     *
     * @param CreditCard $value
     * @return $this
     */
    public function setCard($value)
    {
        if ($value && !$value instanceof CreditCard) {
            $value = new CreditCard($value);
        }

        return $this->setParameter('card', $value);
    }

    protected function getGpCardObj()
    {
        $gpCardObj = new CreditCardData();

        if ($this->getCard()) {
            $omnipayCardObj = $this->getCard();
            
            $gpCardObj->number = $omnipayCardObj->getNumber();
            $gpCardObj->expMonth = $omnipayCardObj->getExpiryMonth();
            $gpCardObj->expYear = $omnipayCardObj->getExpiryYear();
            $gpCardObj->cvn = $omnipayCardObj->getCvv();
            $gpCardObj->cardHolderName = sprintf('%s %s', $omnipayCardObj->getFirstName(), $omnipayCardObj->getLastName());
            $gpCardObj->cardType = $omnipayCardObj->getType();
        }

        if (!empty($this->getToken())) {
            $gpCardObj->token = $this->getToken();
        } elseif (!empty($this->getCardReference())) {
            $gpCardObj->token = $this->getCardReference();
        }

        return $gpCardObj;
    }

    protected function getGpBillingAddyObj()
    {
        $gpAddyObj = new Address();

        if ($this->getCard()) {
            $omnipayCardObj = $this->getCard();

            $gpAddyObj->streetAddress1 = $omnipayCardObj->getBillingAddress1();
            $gpAddyObj->streetAddress2 = $omnipayCardObj->getBillingAddress2();
            $gpAddyObj->city = $omnipayCardObj->getBillingCity();
            $gpAddyObj->postalCode = $omnipayCardObj->getBillingPostcode();
            $gpAddyObj->state = $omnipayCardObj->getBillingState();
            $gpAddyObj->country = $omnipayCardObj->getBillingCountry();
        }

        return $gpAddyObj;
    }

    protected function getGpStoredCredObj()
    {
        $gpStoredCredObj = new StoredCredential();

        if ($this->getCard()) {
            $omnipayCardObj = $this->getCard();
            
            if (!empty($omnipayCardObj->getCardBrandTransId())) {
                $gpStoredCredObj->cardBrandTransactionId = $omnipayCardObj->getCardBrandTransId();
            }

            if (!empty($omnipayCardObj->getStoredCredInitiator())) {
                $gpStoredCredObj->initiator = $omnipayCardObj->getStoredCredInitiator();
            }
        }

        return $gpStoredCredObj;
    }

    /** When send() is call this is called first then sendData() */
    public function getData()
    {
        $this->gpBillingAddyObj = $this->getGpBillingAddyObj();
        $this->gpCardObj = $this->getGpCardObj();
        $this->gpStoredCredObj = $this->getGpStoredCredObj();
    }




    public function setTermid($value)
    {
        return $this->setParameter('termid',$value);
    }

    public function setCertStr($value)
    {
        return $this->setParameter('certStr',$value);
    }

    public function setAuthToken($value)
    {
        return $this->setParameter('AuthToken',$value);
    }

    public function setBillerAccountId($value)
    {
        return $this->setParameter('BillerAccountId',$value);
    }

    public function setHostedTransactionIdentifier($value)
    {
        return $this->setParameter('HostedTransactionIdentifier',$value);
    }

    public function getTermid()
    {
        return $this->getParameter('termid');
    }

    public function getCertStr()
    {
        return $this->getParameter('certStr');
    }

    public function getAuthToken()
    {
        return $this->getParameter('AuthToken');
    }

    public function getBillerAccountId()
    {
        return $this->getParameter('BillerAccountId');
    }

    public function getDeviceId()
    {
        return $this->getParameter('deviceId');
    }

    public function setDeviceId($value)
    {
        return $this->setParameter('deviceId', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getDeveloperId()
    {
        return $this->getParameter('developerId');
    }

    public function setDeveloperId($value)
    {
        return $this->setParameter('developerId', $value);
    }

    public function getGoodReponseCodes()
    {
        return $this->getParameter('goodResponseCodes');
    }

    public function setGoodResponseCodes($value)
    {
        return $this->setParameter('goodResponseCodes', $value);
    }

    public function setFeeAmount($value)
    {
        return $this->setParameter('feeAmount', $value);
    }

    public function getFeeAmount()
    {
        return $this->getParameter('feeAmount');
    }

    public function setWithAccountNumber($value)
    {
        return $this->setParameter('withAccountNumber', $value);
    }

    public function setWithReceivingAccountNumber($value)
    {
        return $this->setParameter('withReceivingAccountNumber', $value);
    }

    public function getWithAccountNumber()
    {
        return $this->getParameter('withAccountNumber');
    }

    public function getWithReceivingAccountNumber()
    {
        return $this->getParameter('withReceivingAccountNumber');
    }

    public function getHostedTransactionIdentifier()
    {
        return $this->getParameter('HostedTransactionIdentifier');
    }

    public function getAuthHeader()
    {
        return $this->getParameter('BillerAccountId') . ":" . $this->getParameter('AuthToken');
    }

    public function getCertAuthHeader(){
        return $this->getParameter('certStr') . ":" . $this->getParameter('termid');
    }

    /**
     * Additional 
     */


    public function getProfileName()
    {
        return $this->getParameter('ProfileName');
    }

    public function setProfileName($value)
    {
        return $this->setParameter('ProfileName', $value);
    }

    public function getMerchantOnboarding(){
        return $this->getParameter('MerchantOnboarding');
    }

    public function setMerchantOnboarding($value)
    {
        return $this->setParameter('MerchantOnboarding', $value);
    }


}
