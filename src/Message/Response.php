<?php namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\OnePay\Traits\HasCheckSumTrait;

/**
 * The Response abstraction
 */
class Response extends AbstractResponse
{
    use HasCheckSumTrait;

    /**
     * Initialize response instance
     *
     * @param RequestInterface $request
     * @param mixed            $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        if (!is_array($data)) {
            parse_str($data, $this->data);
        } else {
            $this->data = $data;
        }

        $this->setCheckSum($this->data, $this->request->getHashCode());
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getCode() === '0' && $this->isHashMatch();
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->getCode() === '99' && $this->isHashMatch();
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        return $this->getCode() === '0' && !$this->isHashMatch();
    }

    /**
     * Determine if secure hash response from server match with computed hash value
     *
     * @return boolean
     */
    protected function isHashMatch()
    {
        return (isset($this->data['vpc_SecureHash']) && strtoupper($this->data['vpc_SecureHash']) == strtoupper($this->getCheckSum()));
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        if (isset($this->data['vpc_MerchTxnRef'])) {
            return $this->data['vpc_MerchTxnRef'];
        }

        return null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        if (isset($this->data['vpc_TransactionNo'])) {
            return $this->data['vpc_TransactionNo'];
        }

        return null;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        if (isset($this->data['vpc_TxnResponseCode'])) {
            return $this->data['vpc_TxnResponseCode'];
        }

        return null;
    }

    /**
     * Alias of the getCode() method
     *
     * @return null|string
     */
    public function getResponseCode()
    {
        return $this->getCode();
    }

    /**
     * Get message
     *
     * @return null|string Description of response code or message from server
     */
    public function getMessage()
    {
        if (isset($this->data['vpc_Message'])) {
            return $this->data['vpc_Message'];
        }

        if (isset($this->data['vpc_TxnResponseCode'])) {
            return $this->getResponseDescription($this->data['vpc_TxnResponseCode']);
        }

        return null;
    }

    /**
     * Get response description
     *
     * @param  string $responseCode
     *
     * @return string Desciption of response code
     */
    public function getResponseDescription($responseCode)
    {
        if (array_key_exists($responseCode, $this->responseCodes)) {
            return $this->responseCodes[$responseCode];
        }

        return 'Unable to be determined the error information. Code: ' . $responseCode;
    }
}