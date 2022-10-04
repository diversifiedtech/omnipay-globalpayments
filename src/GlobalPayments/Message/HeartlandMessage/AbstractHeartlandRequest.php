<?php

namespace Omnipay\GlobalPayments\Message\HeartlandMessage;

use GlobalPayments\Api\PaymentMethods\ECheck as HeartlandECheck;
use GlobalPayments\Api\ServiceConfigs\Gateways\PorticoConfig;
use GlobalPayments\Api\ServicesContainer;
use Omnipay\GlobalPayments\Message\AbstractRequest;

abstract class AbstractHeartlandRequest extends AbstractRequest
{
    protected $gpCheckObj;

    protected function getGpCheckObj()
    {
        $gpCheckObj = new HeartlandECheck();

        if ($this->getCheck()) {
            $omnipayCheckObj = $this->getCheck();

            // Single-Use Token contains/represents account and routing numbers
            if (!empty($this->getToken())) {
                $gpCheckObj->token = $this->getToken();
            } else {
                $gpCheckObj->accountNumber = $omnipayCheckObj->getAccountNumber();
                $gpCheckObj->routingNumber = $omnipayCheckObj->getRoutingNumber();
            }

            $gpCheckObj->accountType      = $omnipayCheckObj->getAccountType();
            $gpCheckObj->checkType        = $omnipayCheckObj->getCheckType();
            $gpCheckObj->secCode          = $omnipayCheckObj->getSecCode();
            if($omnipayCheckObj->getCheckHolderName() != null){
                $gpCheckObj->checkHolderName  = $omnipayCheckObj->getCheckHolderName();
            }else{
                $gpCheckObj->checkHolderName  = ($omnipayCheckObj->getFirstName() . " " .  $omnipayCheckObj->getLastName());
            }
        }

        return $gpCheckObj;
    }

    public function getData()
    {
        parent::getData();

        $this->gpCheckObj = $this->getGpCheckObj();
    }

    protected function setServicesConfig()
    {
        $config = new PorticoConfig();

        if (!empty($this->getSecretApiKey())) {
            $config->secretApiKey = trim($this->getSecretApiKey());
        } else {
            $config->siteId     = $this->getSiteId();
            $config->licenseId  = $this->getLicenseId();
            $config->deviceId   = $this->getDeviceId();
            $config->username   = $this->getUsername();
            $config->password   = $this->getPassword();
        }

        $config->developerId    = $this->getDeveloperId();
        $config->versionNumber  = $this->getVersionNumber();

        ServicesContainer::configureService($config);
    }

    public function getSecretApiKey()
    {
        return $this->getParameter('secretApiKey');
    }

    public function setSecretApiKey($value)
    {
        return $this->setParameter('secretApiKey', $value);
    }

    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    public function getLicenseId()
    {
        return $this->getParameter('licenseId');
    }

    public function setLicenseId($value)
    {
        return $this->setParameter('licenseId', $value);
    }

    public function getVersionNumber()
    {
        return $this->getParameter('versionNumber');
    }

    public function setVersionNumber($value)
    {
        return $this->setParameter('versionNumber', $value);
    }

    public function getCheck()
    {
        return $this->getParameter('check');
    }

    public function setCheck($value)
    {
        return $this->setParameter('check', $value);
    }

    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    public function setCustomer($value)
    {
        return $this->setParameter('customer', $value);
    }

    public function getPaymentMethodReference()
    {
        return $this->getParameter('paymentMethodReference');
    }

    public function setPaymentMethodReference($value)
    {
        return $this->setParameter('paymentMethodReference', $value);
    }

    public function sendData($data)
    {
        parent::sendData($data);

        return new HeartlandResponse($this, $this->runTrans());
    }
}
