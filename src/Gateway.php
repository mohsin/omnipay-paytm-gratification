<?php

namespace Omnipay\PaytmGratification;

use Omnipay\Common\AbstractGateway;

/**
 * Paytm Gratification Gateway.
 *
 * @link http://paywithpaytm.com/developer.html
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PaytmGratification';
    }

    public function getDefaultParameters()
    {
        $defaultPostData = [
            'requestType'         => 'VERIFY',
            'merchantGuid'        => '7e1d580c-aadb-4531-9814-b3c514d55e36',
            'merchantOrderId'     => 'ORDS'.rand(10000, 99999999),
            'salesWalletName'     => '',
            'salesWalletGuid'     => '1fad3a02-2ecd-4a04-970e-abe2723035ce',
            'payeeEmailId'        => '',
            'payeePhoneNumber'    => '7777777777',
            'payeeSsoId'          => '',
            'appliedToNewUsers'   => 'Y',
            'amount'              => '1',
            'currencyCode'        => 'INR'
        ];
        return [
            'request'           => json_encode($defaultPostData),
            'metadata'          => 'Testing Data',
            'ipAddress'         => '127.0.0.1',
            'operationType'     => 'SALES_TO_USER_CREDIT',
            'platformName'      => 'PayTM'
        ];
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

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PaytmGratification\Message\PurchaseRequest', $parameters);
    }
}
