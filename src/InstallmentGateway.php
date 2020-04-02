<?php

namespace Omnipay\OnePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\OnePay\Traits\ParamsAccessorMutatorTrait;

class InstallmentGateway extends AbstractGateway
{
    use ParamsAccessorMutatorTrait;

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     *
     * @return string
     */
    public function getName()
    {
        return 'OnePay Installment';
    }

    /**
     * Define gateway parameters, in the following format:
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'merchant' => '',
            'accessCode' => '',
            'hashCode' => '',
            'user' => '',
            'password' => '',
            'testMode' => false,
        ];
    }

    /**
     * Create a payment request for an invoice.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\OnePay\Message\InstallmentPurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\InstallmentPurchaseRequest', $parameters);
    }

    /**
     * Create a request to check the status of payment after purchase based
     * on the parameters returned on the browser.
     *
     * This function is usually executed on the return page provided to
     * OnePay.
     *
     * @param  array $parameters
     *
     * @return \Omnipay\OnePay\Message\InstallmentCompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\InstallmentCompletePurchaseRequest', $parameters);
    }

    /**
     * Create a request to check the status of the purchase transaction,
     * based on the transaction code from the merchant website.
     *
     *
     * @param  array $parameters
     *
     * @return \Omnipay\OnePay\Message\InstallmentFetchCheckoutRequest
     */
    public function fetchCheckout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\OnePay\Message\InstallmentFetchCheckoutRequest', $parameters);
    }
}