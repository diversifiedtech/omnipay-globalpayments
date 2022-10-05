<?php
namespace Omnipay\GlobalPayments\Tests;

use GlobalPayments\Api\Entities\Enums\AccountType;
use GlobalPayments\Api\Entities\Enums\CardType;
use GlobalPayments\Api\Entities\Enums\CheckType;
use GlobalPayments\Api\Entities\Enums\SecCode;
use Omnipay\GlobalPayments\CreditCard;
use Omnipay\GlobalPayments\ECheck;
use Omnipay\Omnipay;
use Omnipay\Tests\TestCase;

/**
 * Integration tests for the  Gateway. These tests make real requests to Heartland sandbox environment
 */
class HeartlandEcommerceTest extends TestCase
{
    protected $gateway;
    protected $publicKey = 'pkapi_cert_3ZjQJbCO9rygPdXFkd';
    protected $secretAPIKey = 'skapi_cert_McU0AgBkx2EAldEfhhtolMw0RnvahBQAnXFdLYga-Q'; // 777701408656

    public function setUp() : void
    {
        parent::setUp();

        $this->gateway = Omnipay::create('GlobalPayments\Heartland');
        $this->gateway->setSecretApiKey($this->secretAPIKey);
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
     * Purchase attempts using actual Heartland Single-Use Tokens
     */

    public function test07PurchaseVisaSingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getVisa()),
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

    public function test08PurchaseMastercardSingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getMasterCard()),
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

    public function test09PurchaseMastercardBin2SingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getMasterCard2Bin()),
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

    public function test10PurchaseDiscoverSingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getDiscover()),
            'currency' => 'USD',
            'amount' => '20.10'
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test11PurchaseAmexSingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getAmex()),
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

    public function test12PurchaseJcbSingleUseToken()
    {        
        $request = $this->gateway->purchase(
            array(
            'token' => $this->getSingleUseToken($this->getJcb()),
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
     * Test auth-only and subsequent capture
     */

    public function test13AuthAndCapture()
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

    public function test14AuthAndPartialCapture()
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

    public function test15AuthAndNoCapture()
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

    public function test16RefundFull()
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

    public function test17RefundPartial()
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

    public function test18VoidFull()
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

    public function test19VoidPartial()
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

    public function test20CreateCardVisa()
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

    public function test21CreateCardMastercard()
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

    public function test22CreateCardMastercardBin2()
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

    public function test23CreateCardDiscover()
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

    public function test24CreateCardAmex()
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

    public function test25CreateCardJcb()
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

    public function test26PurchaseUsingVisaCardReference()
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

    public function test27PurchaseUsingAmexCardReference()
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

    public function test28DeleteMastercardCardReference()
    {
        // Requires Heartland Multi-Use Tokens be enabled
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getMasterCard()
            )
        );

        $response = $request->send();        
        $cardReference = $response->getCardReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());

        $request = $this->gateway->deleteCard(
            array(
            'cardReference' => $cardReference
            )
        );

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function test29UpdateDiscoverCardReference()
    {
        // Requires Heartland Multi-Use Tokens be enabled
        $request = $this->gateway->createCard(
            array(
            'card' => $this->getDiscover()
            )
        );

        $response = $request->send();        
        $cardReference = $response->getCardReference();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
        $this->assertNotNull($response->getCardReference());

        $request = $this->gateway->updateCard(
            array(
            'card' => new CreditCard(
                array(
                    'expiryYear' => '2026',
                    'expiryMonth' => '1'
                )
            ),
            'cardReference' => $cardReference
            )
        );
        
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function test30AchPurchase()
    {
        // Requires Heartland ACH be enabled
        $request = $this->gateway->purchase(
            array(
            'check' => $this->getPersonalCheck(),
            'currency' => 'USD',
            'amount' => $this->randAmount(2)
            )
        );
        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
        $this->assertNotNull($response->getCode());
    }

    public function test31AchPurchaseSingleUseToken()
    {
        // simulate these values being sent only via Single Use Token
        $check = $this->getPersonalCheck();
        $check->setAccountNumber(null);
        $check->setRoutingNumber(null);

        $request = $this->gateway->purchase(
            array(
            'token' => $this->getAchSingleUseToken($this->getPersonalCheck()),
            'check' => $check,
            'currency' => 'USD',
            'amount' => $this->randAmount(2)
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
            'billingAddress1' => '6860 Dallas Pkwy',
            'billingPostcode' => '75024'
        );

        return new CreditCard($card);
    }

    private function getDiscover()
    {
        $card = array(
            'number' => '6011000990156527',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::DISCOVER,
            'billingAddress1' => '6860',
            'billingPostcode' => '750241234'
        );

        return new CreditCard($card);
    }

    private function getMasterCard()
    {
        $card = array(
            'number' => '5473500000000014',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::MASTERCARD,
            'billingAddress1' => '6860 Dallas Pkwy',
            'billingPostcode' => '75024'
        );

        return new CreditCard($card);
    }

    private function getJcb()
    {
        $card = array(
            'number' => '3566007770007321',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::JCB,
            'billingAddress1' => '6860',
            'billingPostcode' => '75024'
        );

        return new CreditCard($card);
    }

    private function getAmex()
    {
        $card = array(
            'number' => '372700699251018',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 1234,
            'type' => CardType::AMEX,
            'billingAddress1' => '6860',
            'billingPostcode' => '75024'
        );

        return new CreditCard($card);
    }

    private function getVisa()
    {
        $card = array(
            'number' => '4012002000060016',
            'expiryMonth' => 12,
            'expiryYear' => 2025,
            'cvv' => 123,
            'type' => CardType::VISA,
            'billingAddress1' => '6860 Dallas Pkwy',
            'billingPostcode' => '750241234'
        );

        return new CreditCard($card);
    }

    private function getPersonalCheck()
    {
        $check = array(
            'accountNumber' => '1357902468',
            'routingNumber' => '122000030',
        );

        
        $eCheck = new ECheck($check);
        $eCheck->setSecCode(SecCode::WEB);
        $eCheck->setAccountType(AccountType::CHECKING);
        $eCheck->setCheckType(CheckType::PERSONAL);
        $eCheck->setBillingAddress1('6860 Dallas Pkwy');
        $eCheck->setBillingPostcode('750241234');
        $eCheck->setCheckHolderName('Tony Smedal');

        return $eCheck;
    }

    protected function getSingleUseToken(CreditCard $card)
    {
        $cardNo = $card->getNumber();
        $expMonth = $card->getExpiryMonth();
        $expYear = $card->getExpiryYear();
        $cvv = $card->getCvv();

        $curl = curl_init();

        curl_setopt_array(
            $curl, array(
            CURLOPT_URL => 'https://cert.api2.heartlandportico.com/Hps.Exchange.PosGateway.Hpf.v1/api/token?api_key=' . $this->publicKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"object":"token","token_type":"supt","card":{"number": ' . $cardNo . ', "cvc": ' . $cvv . ', "exp_month": ' . $expMonth . ', "exp_year": ' . $expYear . '}}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            )
        );

        $responseAsObj = json_decode(curl_exec($curl));

        curl_close($curl);

        return $responseAsObj->token_value;
    }

    protected function getAchSingleUseToken(ECheck $check)
    {
        $accountNumber = $check->getAccountNumber();
        $routingNumber = $check->getRoutingNumber();

        $curl = curl_init();

        curl_setopt_array(
            $curl, array(
            CURLOPT_URL => 'https://cert.api2.heartlandportico.com/Hps.Exchange.PosGateway.Hpf.v1/api/token?api_key=' . $this->publicKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"object": "token", "token_type": "supt", "ach": {"account_number": "'. $accountNumber . '", "routing_number": "' . $routingNumber . '"}}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            )
        );

        $responseAsObj = json_decode(curl_exec($curl));

        curl_close($curl);

        return $responseAsObj->token_value;
    }
}
