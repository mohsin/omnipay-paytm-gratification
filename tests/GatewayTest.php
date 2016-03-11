<?php

namespace Omnipay\PaytmGratification;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase {

    protected $orderId;

    public function setUp()
    {
      parent::setUp();

      $this -> gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
      $this -> gateway -> setMerchantKey('2s@J%bd2Ir8Lb8Q4');
      $this -> gateway -> setTestMode(true);

      $this -> orderId = 'ORDS'.rand(10000, 99999999);

      $postData = [
          'requestType'         => 'VERIFY',
          'merchantGuid'        => '7e1d580c-aadb-4531-9814-b3c514d55e36',
          'merchantOrderId'     => $this -> orderId,
          'salesWalletName'     => '',
          'salesWalletGuid'     => '1fad3a02-2ecd-4a04-970e-abe2723035ce',
          'payeeEmailId'        => '',
          'payeePhoneNumber'    => '7777777777',
          'payeeSsoId'          => '',
          'appliedToNewUsers'   => 'Y',
          'amount'              => '10',
          'currencyCode'        => 'INR'
      ];

      $this->options = [
          'request' => json_encode($postData),
      ];
    }

    /**
     * @test
     */
    public function verify_purchase_was_successful()
    {
      $response = $this -> gateway -> purchase($this->options) -> send();
      $this->assertTrue($response->isSuccessful());
      $this->assertFalse($response->isRedirect());
      $this->assertEquals('SUCCESS', $response->getCode());
      $this->assertEquals('SUCCESS', $response->getMessage());
    }

    /**
     * @test
     */
    public function testCompletePurchaseError()
    {
        $postData = [
            'requestType'         => 'VERIFY',
            'merchantGuid'        => '7e1d580c-aadb-4531-9814-b3c514d55e36',
            'merchantOrderId'     => $this -> orderId,
            'salesWalletName'     => '',
            'salesWalletGuid'     => '1fad3a02-2ecd-4a04-970e-abe2723035ce',
            'payeeEmailId'        => '',
            'payeePhoneNumber'    => '1234567890', //Invalid number
            'payeeSsoId'          => '',
            'appliedToNewUsers'   => 'Y',
            'amount'              => '10',
            'currencyCode'        => 'INR'
        ];

        $options = [
            'request' => json_encode($postData),
        ];

        $response = $this->gateway->purchase($options)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals($this -> orderId, $response->getTransactionId());
        $this->assertEquals('GE_1032', $response->getCode());
        $this->assertEquals('Please enter a valid mobile', $response->getMessage());
    }

}
