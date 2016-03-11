<?php

namespace Omnipay\PaytmGratification\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaytmGratification\PaytmHelpers;

/**
 * Paytm Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
{
    use PaytmHelpers;

    protected $liveEndpoint = 'https://trust.paytm.in/wallet-web/salesToUserCredit';
    protected $testEndpoint = 'http://trust-uat.paytm.in/wallet-web/salesToUserCredit';

    protected $checkSumHash;

    public function getData()
    {
        $data['request'] = $this->getRequest();
        $data['metadata'] = $this->getMetadata();
        $data['ipAddress'] = $this->getIpAddress();
        $data['operationType'] = $this->getOperationType();
        $data['platformName'] = $this->getPlatformName();

        // Generate the checkSumHash
        $this -> checkSumHash = $this -> getChecksumHash($data);

        return $data;
    }

    public function sendData($data)
    {
        $data['request'] = json_decode($data['request'], true);

        try {
            /*$response = $this->httpClient->post(
                $this->getEndpoint(),
                $this->getApiHeader(),
                json_encode($data)
            );*/
            /*$request = $this->httpClient->post($this->getEndpoint());
            $request->getCurlOptions()->set(CURLOPT_POST, 1);
            $request->getCurlOptions()->set(CURLOPT_POSTFIELDS, json_encode($data));
            $request->getCurlOptions()->set(CURLOPT_RETURNTRANSFER, true);
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
            $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
            $request->getCurlOptions()->set(CURLOPT_HTTPHEADER, $this->getApiHeader());
            $response = $request -> send();

            $data = $response->json();
            var_dump($data);*/

            // TODO: Convert this to Guzzle
            $ch = curl_init();  // initiate curl
            curl_setopt($ch, CURLOPT_URL, $this->getEndpoint());
            curl_setopt($ch, CURLOPT_POST, 1);  // tell curl you want to post something
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // define what you want to post
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getApiHeader());
            $output = curl_exec($ch); // execute
            $info = curl_getinfo($ch);
            return new PurchaseResponse($this, json_decode($output, true)); //$response->json()
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            return new PurchaseResponse($this, json_decode($output, true));
        }
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getRequest()
    {
        return $this->getParameter('request');
    }

    public function setRequest($value)
    {
        return $this->setParameter('request', $value);
    }

    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    public function getIpAddress()
    {
        return $this->getParameter('ipAddress');
    }

    public function setIpAddress($value)
    {
        return $this->setParameter('ipAddress', $value);
    }

    public function getOperationType()
    {
        return $this->getParameter('operationType');
    }

    public function setOperationType($value)
    {
        return $this->setParameter('operationType', $value);
    }

    public function getPlatformName()
    {
        return $this->getParameter('platformName');
    }

    public function setPlatformName($value)
    {
        return $this->setParameter('platformName', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('MerchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('MerchantKey', $value);
    }

    public function getChecksumHash($data)
    {
        $merchantKey = $this -> getMerchantKey();
        $tempData = $data;
        $tempData['request'] = json_decode($tempData['request'], true);
        return $this -> getChecksumFromString(json_encode($tempData), $merchantKey);
    }

    public function getApiHeader()
    {
        return array(
            'Content-Type:application/json',
            'mid:' . json_decode($this -> getRequest()) -> merchantGuid,
            'checksumhash:' . $this -> checkSumHash
        );
    }
}
