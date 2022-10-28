<?php

namespace Omnipay\GlobalPayments\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Exception\RuntimeException;

class ProPayMerchantOnboardingResponse extends CommonAbstractResponse
{
    protected $payer_id;

    public const __SUCCESS_CODES = ['00', '10'];

    public function __construct($request, $data)
    {
        $this->request = $request;

        $result = json_decode($data);

        if (!$result || !$result->Status) {
            throw new RuntimeException('Invalid Response From Gateway');
        }

        $this->response = $result;
    }


    public function isSuccessful()
    {
        return in_array($this->response->Status, static::__SUCCESS_CODES);
    }

    public function getAccountNumber()
    {
        return $this->response->AccountNumber ?? null;
    }
    public function getStatus()
    {
        return $this->response->Status ?? null;
    }

    public function getBeneficialOwnerDataResult()
    {
        return $this->response->BeneficialOwnerDataResult ?? null;
    }

    public function getPassword()
    {
        return $this->response->Password ?? null;
    }

    public function getSourceEmail()
    {
        return $this->response->SourceEmail ?? null;
    }


    public function getTier()
    {
        return $this->response->Tier ?? null;
    }




    public function getMessage()
    {
        return $this->response->RequestResult->ResultMessage ?? null;
    }

    public function getCode()
    {
        return $this->response->RequestResult->ResultCode ?? null;
    }

    public function getData()
    {
        if ($this->response == null) {
            return [];
        }
        $data = $this->object_to_array($this->response);

        return $data;
    }

    protected function object_to_array($obj)
    {
        //only process if it's an object or array being passed to the function
        if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach ($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = $this->object_to_array($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }
}
