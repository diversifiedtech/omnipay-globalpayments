<?php

namespace Omnipay\GlobalPayments;

use Omnipay\Common\AbstractGateway;

class HeartlandGateway extends AbstractGateway
{
    private $heartlandMessagePath = '\Omnipay\GlobalPayments\Message\HeartlandMessage';

    public function getName()
    {
        return 'Heartland';
    }

    public function getDefaultParameters()
    {
        return array(
            'secretApiKey' => '',
            'siteId' => '',
            'deviceId' => '',
            'licenseId' => '',
            'username' => '',
            'password' => '',
            'developerId' => '002914',
            'versionNumber' => '4285',
            'siteTrace' => ''
        );
    }

    // Methods for setting Gateway Authentication properties
    
    public function setSecretApiKey($value)
    {
        return $this->setParameter('secretApiKey', $value);
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    public function setDeviceId($value)
    {
        return $this->setParameter('deviceId', $value);
    }

    public function setUserName($value)
    {
        return $this->setParameter('userName', $value);
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function setDeveloperId($value)
    {
        return $this->setParameter('developerId', $value);
    }

    public function setVersionNumber($value)
    {
        return $this->setParameter('versionNumber', $value);
    }

    public function setSiteTrace($value)
    {
        return $this->setParameter('siteTrace', $value);
    }

    // Transactions

    public function purchase($options = array())
    {
        if (isset($options['check'])) {
            return $this->createRequest(
                $this->heartlandMessagePath . '\ACHPurchaseRequest', $options
            );
        }

        return $this->createRequest(
            $this->heartlandMessagePath . '\PurchaseRequest', $options
        );
    }

    public function authorize($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\AuthorizeRequest', $options
        );
    }

    public function capture($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\CaptureRequest', $options
        );
    }

    public function void($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\VoidRequest', $options
        );
    }

    public function refund($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\RefundRequest', $options
        );
    }

    public function createCard($options = array())
    {
        if (isset($options['check'])) {
            return $this->createRequest(
                $this->heartlandMessagePath . '\CreateAchRequest', $options
            );
        }

        return $this->createRequest(
            $this->heartlandMessagePath . '\CreateCardRequest', $options
        );
    }

    public function updateCard($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\UpdateCardRequest', $options
        );
    }

    public function deleteCard($options = array())
    {
        return $this->createRequest(
            $this->heartlandMessagePath . '\DeleteCardRequest', $options
        );
    }
}
