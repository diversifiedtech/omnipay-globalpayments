<?php
namespace Tests\Certification\Transit;

use GlobalPayments\Api\Entities\Enums\CardType;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\ServiceConfigs\AcceptorConfig;
use GlobalPayments\Api\ServiceConfigs\Gateways\TransitConfig;
use GlobalPayments\Api\Services\BatchService;
use GlobalPayments\Api\ServicesContainer;
use Omnipay\Omnipay;
use Omnipay\Tests\TestCase;

/**
 * Integration tests for the Transit Gateway. These tests make real requests to Heartland sandbox environment.
 */
class EcommerceTab extends TestCase
{
    private static $partialVoidTarget;
    private static $fullVoidTarget;
    private static $visaReference;
    private static $mastercardReference;
    private static $refundTarget;

    // will remove these vars after de-jankifying TSEP handling
    private $tsepMasterCard2Bin = '6GrNPfmCCjdq0011';
    private $tsepDiscover = 'LcsepTJXhdK86909';
    private $tsepMasterCard = 'Ch0WiL69BQ7Q0055';
    private $tsepJcb = '4sZHIIMj6oKS5859';
    private $tsepAmex = 'oHeftkZLJkh2376';
    private $tsepVisa = 'IYLPXjKmrTpm5439';
    private $tsepDiscuverCup = 'uu3pWBH1vhf62342';
    private $tsepDiners = 'jnaYZrd7vsDW0018';

    public function setUp() : void
    {
        parent::setUp();
        $transactionKey = 'DPJLWWAD1MOAX8XPCHZAXP15U0UME5U0';
        $deviceId = '88500000322601';
        $merchantId = '885000003226';
        $userName = 'TA5748226';
        if ($deviceId) {
            $this->gateway = Omnipay::create('GlobalPayments\Transit');
            $this->gateway->setDeviceId($deviceId);
            $this->gateway->setMerchantId($merchantId);
            $this->gateway->setUserName($userName);
            $this->gateway->setTransactionKey($transactionKey);
        }
    }

    public function testGetonustoken01()
    {
        $card = array(
            'number' => '4012000098765439',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 999,
            'type' => CardType::VISA
        );

        $response = $this->gateway->createCard(
            array(
                'card' => $card,
            )
        )->send();

        $this->assertTrue($response->isSuccessful());
    }

    // public function testGetonustoken01Spoof()
    // {
    //     $config = new TransitConfig(); // replaces ServicesConfig()
    //     $config->merchantId = '885000003226';
    //     $config->deviceId = '88500000322601';
    //     $config->transactionKey = 'DPJLWWAD1MOAX8XPCHZAXP15U0UME5U0';
    //     $config->acceptorConfig = new AcceptorConfig();

    //     ServicesContainer::configureService($config);

    //     $card = new CreditCardData();
    //     $card->number = '4012000098765439';
    //     $card->expMonth = '12';
    //     $card->expYear = 2025;
    //     $card->cvn = 999;
    //     $card->cardType = CardType::VISA;

    //     $response = $card->tokenize()->execute();

    //     $this->assertEquals($response->responseCode, '00');
    // }

    public function testGetonustoken02()
    {
        $card = array(
            'number' => '5146315000000055',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 998,
            'type' => CardType::MASTERCARD
        );

        $response = $this->gateway->createCard(
            array(
                'card' => $card,
            )
        )->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase01()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getMasterCard2Bin(),
            'token' => $this->tsepMasterCard2Bin,
            'currency' => 'USD',
            'amount' => 11.10
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase02()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getDiscover(),
            'token' => $this->tsepDiscover,
            'currency' => 'USD',
            'amount' => '12.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase03()
    {
        $request = $this->gateway->authorize(
            array(
            'card' => $this->getDiners(),
            'token' => $this->tsepDiners,
            'currency' => 'USD',
            'amount' => '6.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase04()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getMasterCard(),
            'token' => $this->tsepMasterCard,
            'currency' => 'USD',
            'amount' => '15.00'
            )
        );

        $response = $request->send();
        static::$partialVoidTarget = $response->getTransactionReference();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase05()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getMasterCard(),
            'token' => $this->tsepDiners,
            'currency' => 'USD',
            'amount' => 34.13
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase06()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getJcb(),
            'token' => $this->tsepJcb,
            'currency' => 'USD',
            'amount' => '13.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase07()
    {
        $card = $this->getAmex();
        unset($card['cvv']); // test specifies not to send

        $request = $this->gateway->purchase(
            array(
            'card' => $card,
            'token' => $this->tsepAmex,
            'currency' => 'USD',
            'amount' => 13.50
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase08()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'token' => $this->tsepVisa,
            'currency' => 'USD',
            'amount' => 32.49
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase09()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getDiscoverCup(),
            'token' => $this->tsepDiscuverCup,
            'currency' => 'USD',
            'amount' => '10.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase10()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'token' => $this->tsepVisa,
            'currency' => 'USD',
            'amount' => 11.12
            )
        );

        $response = $request->send();
        static::$refundTarget = $response->getTransactionReference();
        $this->assertTrue($response->isSuccessful());
    }

    public function testInternetPurchase11()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getAmex(),
            'token' => $this->tsepAmex,
            'currency' => 'USD',
            'amount' => '4.00'
            )
        );

        $response = $request->send();
        static::$fullVoidTarget = $response->getTransactionReference();
        $this->assertTrue($response->isSuccessful());
    }

    // public function testCardAuthentication01()
    // {
    //     $request = $this->gateway->createCard(array(
    //         'card' => $this->getVisa()
    //     ));

    //     $response = $request->send();
    //     static::$visaReference = $response->getCardReference();

    //     $this->assertTrue($response->isSuccessful());
    // }

    // public function testCardAuthentication02()
    // {
    //     $request = $this->gateway->createCard(array(
    //         'card' => $this->getMasterCard()
    //     ));
    //     $response = $request->send();
    //     static::$mastercardReference = $response->getCardReference();

    //     $this->assertTrue($response->isSuccessful());
    // }

    // public function testCardAuthentication03()
    // {
    //     $request = $this->gateway->createCard(array(
    //         'card' => $this->getAmex()
    //     ));
    //     $response = $request->send();

    //     $this->assertTrue($response->isSuccessful());
    // }

    public function testVoid01()
    {
        $request = $this->gateway->void(
            array(
            'transactionReference' => static::$partialVoidTarget,
            'amount' => '5.00',
            'description' => 'PARTIAL_REVERSAL'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testVoid02()
    {
        $request = $this->gateway->void(
            array(
            'transactionReference' => static::$fullVoidTarget,
            'description' => 'POST_AUTH_USER_DECLINE'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testConsumerInitiated01()
    {
        $request = $this->gateway->purchase(
            array(
            // 'cardReference' => static::$visaReference,
            'cardReference' => $this->tsepVisa,
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => '14.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testConsumerInitiated02()
    {
        $request = $this->gateway->purchase(
            array(
            // 'cardReference' => static::$mastercardReference,
            'cardReference' => $this->tsepMasterCard,
            'card' => $this->getMasterCard(),
            'currency' => 'USD',
            'amount' => '15.00'
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testIntermissionBeforeRefund()
    {
        BatchService::closeBatch();

        // sleep(3601); // only needed during certification
    }

    public function testReturn01()
    {
        $request = $this->gateway->refund(
            array(
            'transactionReference' => static::$refundTarget,
            'currency' => 'USD',
            )
        );

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
    }
    
    // close the batch via SDK directly
    // this won't be needed in production since auto-close exists
    public function testCloseBatch()
    {
        $response = BatchService::closeBatch();
        $this->assertEquals('00', $response->responseCode);
    }

    private function getMasterCard2Bin()
    {
        $card = array(
            // 'number' => '2223000048400011',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 998,
            'type' => CardType::MASTERCARD
        );

        return array_merge($card, $this->avsData);
    }

    private function getDiscover()
    {
        $card = array(
            // 'number' => '6011000993026909',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 996,
            'type' => CardType::DISCOVER
        );

        return array_merge($card, $this->avsData);
    }

    private function getMasterCard()
    {
        $card = array(
            // 'number' => '5146315000000055',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 998,
            'type' => CardType::MASTERCARD
        );

        return array_merge($card, $this->avsData);
    }

    private function getJcb()
    {
        $card = array(
            // 'number' => '3530142019945859',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 996,
            'type' => CardType::JCB
        );

        return array_merge($card, $this->avsData);
    }

    private function getAmex()
    {
        $card = array(
            // 'number' => '371449635392376',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 9997,
            'type' => CardType::AMEX
        );

        return array_merge($card, $this->avsData);
    }

    private function getVisa()
    {
        $card = array(
            // 'number' => '4012000098765439',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 999,
            'type' => CardType::VISA
        );

        return array_merge($card, $this->avsData);
    }

    private function getDiscoverCup()
    {
        $card = array(
            // 'number' => '6282000123842342',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 996,
            'type' => CardType::DISCOVER
        );

        return array_merge($card, $this->avsData);
    }

    private function getDiners()
    {
        $card = array(
            // 'number' => '3055155515160018',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 996,
            'type' => CardType::DINERS
        );

        return array_merge($card, $this->avsData);
    }

    private $avsData = array(
        'billingAddress1' => '8320',
        'billingPostcode' => '85284'
    );
}
