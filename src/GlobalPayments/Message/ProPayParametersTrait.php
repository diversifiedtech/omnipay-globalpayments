<?php

namespace Omnipay\GlobalPayments\Message;

use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\StoredCredentialInitiator;
use GlobalPayments\Api\Entities\StoredCredential;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use Omnipay\GlobalPayments\CreditCard;
use Omnipay\GlobalPayments\Message\ProPayResponse;

trait ProPayParametersTrait
{
    /**
     * Get Payer Paramters
     */
    public function setName($value)
    {
        return $this->setParameter("Name", $value);
    }
    public function getName()
    {
        return $this->getParameter('Name');
    }


    /**
     * Get Hpp Paramters
     */
    public function setAmount($value)
    {
        return $this->setParameter("Amount", $value);
    }
    public function setPayerId($value)
    {
        return $this->setParameter("PayerId", $value);
    }
    public function setMerchantProfileId($value)
    {
        return $this->setParameter("MerchantProfileId", $value);
    }
    public function setAcceptMasterPass($value)
    {
        return $this->setParameter("AcceptMasterPass", $value);
    }
    public function setAvsRequirementType($value)
    {
        return $this->setParameter("AvsRequirementType", $value);
    }
    public function setCardHolderNameRequirementType($value)
    {
        return $this->setParameter("CardHolderNameRequirementType", $value);
    }
    public function setComment1($value)
    {
        return $this->setParameter("Comment1", $value);
    }
    public function setCssUrl($value)
    {
        return $this->setParameter("CssUrl", $value);
    }
    public function setCurrencyCode($value)
    {
        return $this->setParameter("CurrencyCode", $value);
    }
    public function setOnlyStoreCardOnSuccessfulProcess($value)
    {
        return $this->setParameter("OnlyStoreCardOnSuccessfulProcess", $value);
    }
    public function setPaymentTypeId($value)
    {
        return $this->setParameter("PaymentTypeId", $value);
    }
    public function setProcessCard($value)
    {
        return $this->setParameter("ProcessCard", $value);
    }
    public function setProtected($value)
    {
        return $this->setParameter("Protected", $value);
    }
    public function setReturnURL($value)
    {
        return $this->setParameter("ReturnURL", $value);
    }
    public function setSecurityCodeRequirementType($value)
    {
        return $this->setParameter("SecurityCodeRequirementType", $value);
    }
    public function setStoreCard($value)
    {
        return $this->setParameter("StoreCard", $value);
    }
    public function setInvoiceNumber($value)
    {
        return $this->setParameter("InvoiceNumber", $value);
    }


    public function getAmount()
    {
        return $this->getParameter('Amount');
    }
    public function getPayerId()
    {
        return $this->getParameter('PayerId');
    }
    public function getMerchantProfileId()
    {
        return $this->getParameter('MerchantProfileId');
    }
    public function getAcceptMasterPass()
    {
        return $this->getParameter('AcceptMasterPass');
    }
    public function getAvsRequirementType()
    {
        return $this->getParameter('AvsRequirementType');
    }
    public function getCardHolderNameRequirementType()
    {
        return $this->getParameter('CardHolderNameRequirementType');
    }
    public function getComment1()
    {
        return $this->getParameter('Comment1');
    }
    public function getCssUrl()
    {
        return $this->getParameter('CssUrl');
    }
    public function getCurrencyCode()
    {
        return $this->getParameter('CurrencyCode');
    }
    public function getOnlyStoreCardOnSuccessfulProcess()
    {
        return $this->getParameter('OnlyStoreCardOnSuccessfulProcess');
    }
    public function getPaymentTypeId()
    {
        return $this->getParameter('PaymentTypeId');
    }
    public function getProcessCard()
    {
        return $this->getParameter('ProcessCard');
    }
    public function getProtected()
    {
        return $this->getParameter('Protected');
    }
    public function getReturnURL()
    {
        return $this->getParameter('ReturnURL');
    }
    public function getSecurityCodeRequirementType()
    {
        return $this->getParameter('SecurityCodeRequirementType');
    }
    public function getStoreCard()
    {
        return $this->getParameter('StoreCard');
    }
    public function getInvoiceNumber()
    {
        return $this->getParameter("InvoiceNumber");
    }

    /**
     * Get Payer Paramters
     */
    public function getHostedTransactionId(){
        return $this->getParameter("HostedTransactionId");
    }

    public function setHostedTransactionId($value){
        return $this->setParameter("HostedTransactionId",$value);
    }

    /**
     * Split Pay Parameters
     */

    public function getPaymentReference(){
        return $this->getParameter("PaymentReference");
    }

    public function setPaymentReference($value){
        return $this->setParameter("PaymentReference",$value);
    }

    /**
     * Create Merchant Profile Id
     */
    public function getProfileName()
    {
        return $this->getParameter('ProfileName');
    }

    public function setProfileName($value)
    {
        return $this->setParameter('ProfileName', $value);
    }
}
