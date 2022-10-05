<?php
namespace Omnipay\GlobalPayments\Tests;

use GlobalPayments\Api\Entities\Enums\CardType;
use GlobalPayments\Api\Entities\Enums\MobilePaymentMethodType;
use Omnipay\GlobalPayments\CreditCard;
use Omnipay\Omnipay;
use Omnipay\Tests\TestCase;

/**
 * Integration tests for the Genius Gateway. These tests make real requests to the Genius sandbox environment.
 */
class GeniusEcommerceTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $merchantName = 'Test Shane Logsdon';
        $merchantSiteId = 'BKHV2T68';
        $merchantKey = 'AT6AN-ALYJE-YF3AW-3M5NN-UQDG1';

        $this->gateway = Omnipay::create('GlobalPayments\Genius');
        $this->gateway->setMerchantName($merchantName);
        $this->gateway->setMerchantSiteId($merchantSiteId);
        $this->gateway->setMerchantKey($merchantKey);
    }

    /**
     * Basic purchase attempts using raw card numbers
     */

    public function test01PurchaseVisaManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test02PurchaseMastercardManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getMasterCard(),
            'currency' => 'USD',
            'amount' => 20.02
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test03PurchaseMastercardBin2ManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getMasterCard2Bin(),
            'currency' => 'USD',
            'amount' => '20.03'
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test04PurchaseDiscoverManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getDiscover(),
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test05PurchaseAmexManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getAmex(),
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test06PurchaseJcbManualEntry()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getJcb(),
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    /**
     * ApplePay usage attempt
     */

    public function test07ApplePay()
    {
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getApplePay(),
            'currency' => 'USD',
            'amount' => $this->randAmount(),
            'token' => 'ew0KCSJ2ZXJzaW9uIjogIkVDX3YxIiwNCgkiZ==',
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    /**
     * Test auth-only and subsequent capture
     */

    public function test08AuthAndCapture()
    {
        // Authorize
        $request = $this->gateway->authorize(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.14
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Capture
        $request = $this->gateway->capture(
            array(
            'transactionReference' => $response->getTransactionReference()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test09AuthAndPartialCapture()
    {
        // Authorize
        $request = $this->gateway->authorize(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.15
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Capture
        $request = $this->gateway->capture(
            array(
            'transactionReference' => $response->getTransactionReference(),
            'amount' => 5.00
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test10AuthAndNoCapture()
    {
        // Authorize
        $request = $this->gateway->authorize(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.16
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    /**
     * Test refunds
     */

    public function test11RefundFull()
    {
        // Purchase
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.17
            )
        );

        $response = $request->send();
        $purchaseTransactionReference = $response->getTransactionReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Refund
        $request = $this->gateway->refund(
            array(
            'transactionReference' => $purchaseTransactionReference,
            'currency' => 'USD',
            'amount' => 11.17
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test12RefundPartial()
    {
        // Purchase
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.18
            )
        );

        $response = $request->send();
        $purchaseTransactionReference = $response->getTransactionReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Refund
        $request = $this->gateway->refund(
            array(
            'transactionReference' => $purchaseTransactionReference,
            'amount' => 5.00,
            'currency' => 'USD'
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    /**
     * Test voids
     */

    public function test13VoidFull()
    {
        // Purchase
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.19
            )
        );

        $response = $request->send();
        $purchaseTransactionReference = $response->getTransactionReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Void
        $request = $this->gateway->void(
            array(
            'transactionReference' => $purchaseTransactionReference,
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test14VoidPartial()
    {
        // Purchase
        $request = $this->gateway->purchase(
            array(
            'card' => $this->getVisa(),
            'currency' => 'USD',
            'amount' => 11.20
            )
        );

        $response = $request->send();
        $purchaseTransactionReference = $response->getTransactionReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());

        // Void
        $request = $this->gateway->void(
            array(
            'transactionReference' => $purchaseTransactionReference,
            'amount' => 5.00
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    /**
     * Test createCard and cardReference
     */

    public function test15CreateCardVisa()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getVisa()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    public function test16CreateCardMastercard()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getMasterCard()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    public function test17CreateCardMastercardBin2()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getMasterCard2Bin()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    public function test18CreateCardDiscover()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getDiscover()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    public function test19CreateCardAmex()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getAmex()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    public function test20CreateCardJcb()
    {
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getJcb()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());
    }

    /**
     * Test purchases using cardReference
     */

    public function test20PurchaseUsingVisaCardReference()
    {
        // createCard
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getVisa()
            )
        );

        $response = $request->send();
        $cardReference = $response->getCardReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());

        // purchase w/cardReference
        $request = $this->gateway->purchase(
            array(
            'cardReference' => $cardReference,
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test21PurchaseUsingAmexCardReference()
    {
        // createCard
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getAmex()
            )
        );

        $response = $request->send();
        $cardReference = $response->getCardReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());

        // purchase w/cardReference
        $request = $this->gateway->purchase(
            array(
            'cardReference' => $cardReference,
            'currency' => 'USD',
            'amount' => $this->randAmount()
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    protected function randAmount($minDigits = 0, $maxDigits = 4)
    {
        $numstring = '';
        $digits = rand($minDigits, $maxDigits);
        
        for ($x = 0; $x < $digits; $x++)
        {
            $numstring = $numstring . (string) rand(1, 9);
        }

        return (string) $numstring . '.' . (string) number_format(rand(0, 99));
    }

    private function getMasterCard2Bin()
    {
        $card = array(
            'number' => '2223000010005780',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 900,
            'type' => CardType::MASTERCARD,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getDiscover()
    {
        $card = array(
            'number' => '6011000990156527',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::DISCOVER,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getMasterCard()
    {
        $card = array(
            'number' => '5473500000000014',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::MASTERCARD,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getJcb()
    {
        $card = array(
            'number' => '3566007770007321',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::JCB,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getAmex()
    {
        $card = array(
            'number' => '372700699251018',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 1234,
            'type' => CardType::AMEX,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getVisa()
    {
        $card = array(
            'number' => '4012002000060016',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::VISA,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private function getApplePay()
    {
        $card = array(
            'mobileType' => MobilePaymentMethodType::APPLEPAY,
        );

        return new CreditCard(array_merge($card, $this->avsData));
    }

    private $avsData = array(
        'billingAddress1' => '1 Federal Street',
        'billingPostcode' => '02110'
    );
}
