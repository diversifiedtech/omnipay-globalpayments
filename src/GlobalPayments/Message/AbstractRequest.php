<?php

namespace Omnipay\GlobalPayments\Message;

use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\StoredCredentialInitiator;
use GlobalPayments\Api\Entities\StoredCredential;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use Omnipay\GlobalPayments\CreditCard;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $gpBillingAddyObj;
    protected $gpCardObj;
    protected $gpStoredCredObj;

    protected abstract function runTrans();
    protected abstract function setServicesConfig();

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

    public function getData()
    {
        $this->gpBillingAddyObj = $this->getGpBillingAddyObj();
        $this->gpCardObj = $this->getGpCardObj();
        $this->gpStoredCredObj = $this->getGpStoredCredObj();
    }

    public function sendData($data)
    {
        $this->setServicesConfig();
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

    public function setFeeAmount($value){
        return $this->setParameter('feeAmount', $value);
    }

    public function getFeeAmount(){
        return $this->getParameter('feeAmount');
    }
}
