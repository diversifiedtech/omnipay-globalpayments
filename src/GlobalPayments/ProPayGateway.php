<?php

namespace Omnipay\GlobalPayments;

use Omnipay\Common\AbstractGateway;

class ProPayGateway extends AbstractGateway
{
    private $propayMessagePath = '\Omnipay\GlobalPayments\Message\ProPayMessage';
    private $heartlandMessagePath = '\Omnipay\GlobalPayments\Message\HeartlandMessage';

    public function getName()
    {
        return 'ProPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'termid' => '',
            'certStr' => '',
            'AuthToken' => '',
            'BillerAccountId' => '',
            'secretApiKey' => '',
            'withAccountNumber' => '',
            'withReceivingAccountNumber' => '',
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

    public function setTermid($value)
    {
        return $this->setParameter('termid', $value);
    }

    public function setCertStr($value)
    {
        return $this->setParameter('certStr', $value);
    }

    public function setAuthToken($value)
    {
        return $this->setParameter('AuthToken', $value);
    }

    public function setBillerAccountId($value)
    {
        return $this->setParameter('BillerAccountId', $value);
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

    public function getAuthHeader()
    {
        return $this->getParameter('BillerAccountId') . ":" . $this->getParameter('AuthToken');
    }


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

    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    public function setHostedTransactionIdentifier($value)
    {
        return $this->setParameter('HostedTransactionIdentifier',$value);
    }
    public function getHostedTransactionIdentifier()
    {
        return $this->getParameter('HostedTransactionIdentifier');
    }

    
    public function getMerchantProfileId()
    {
        return $this->getParameter('MerchantProfileId');
    }

    public function setMerchantProfileId($value)
    {
        return $this->setParameter("MerchantProfileId", $value);
    }

    // Transactions

    public function getUrl($options = array())
    {
        return $this->createRequest(
            $this->propayMessagePath . '\GetUrlRequest',
            $options
        );
    }

    public function getResults($options = array())
    {
        return $this->createRequest(
            $this->propayMessagePath. '\GetResultsRequest',
            $options
        );
    }

    public function seperateSplitPay($options = array()){
        return $this->splitPay($options);
    }

    public function splitPay($options = array()){
        return $this->createRequest($this->propayMessagePath . '\SeperateSplitPayRequest', $options);
    }

    public function purchaseStored($options = array()){
        return $this->createRequest($this->propayMessagePath . '\PurchaseStoredRequest', $options);
    }

    public function splitPayReverse($options = array()){
        return $this->createRequest($this->propayMessagePath . '\SeperateReverseSplitPayRequest', $options);
    }

    public function createMerchantProfileId($options = array()){
        return $this->createRequest($this->propayMessagePath . '\CreateMerchantProfileRequest', $options);
    }

    public function createMerchant($options = array()){
        return $this->createRequest($this->propayMessagePath . '\CreateMerchantRequest', $options);
    }


    public function porticoPurchase($options = array()){
        return $this->createRequest($this->heartlandMessagePath . '\PurchaseRequest', $options);
    }

    public function purchase($options = array())
    {
        if (isset($options['check'])) {
            return $this->createRequest(
                $this->propayMessagePath . '\ACHPurchaseRequest',
                $options
            );
        }

        if (isset($options['protectPay'])) {
            return $this->propayCard($options);
        }

        if (isset($options['propaycheck'])) {
            return $this->propayAch($options);
        }

        return $this->createRequest(
            $this->propayMessagePath . '\PurchaseRequest',
            $options
        );
    }
}
