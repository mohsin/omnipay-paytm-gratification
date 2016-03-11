<?php

namespace Omnipay\PaytmGratification\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Paytm Purchase Response.
 */
class PurchaseResponse extends AbstractResponse implements ResponseInterface
{
    public function getRequest()
    {
        return $this->request;
    }

    public function isSuccessful()
    {
        return $this->data['statusCode'] === "SUCCESS";
    }

    public function getCode()
    {
        return isset($this->data['statusCode']) ? $this->data['statusCode'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['statusMessage']) ? $this->data['statusMessage'] : null;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getTransactionId()
    {
        return isset($this->data['orderId']) ? $this->data['orderId'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['metadata']) ? $this->data['metadata'] : null;
    }
}
