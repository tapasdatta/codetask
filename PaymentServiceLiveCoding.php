<?php

interface PaymentGatewayInterface
{
    public function make($amount, $user);
}

class PaymentService
{
    public function __construct(private PaymentGatewayInterface $payment)
    {
        //
    }

    public function charge(float $amount, $user)
    {
        return $this->payment->make($amount, $user);
    }
}

abstract class BasePaymentGateway implements PaymentGatewayInterface
{
    protected $deliverCost;
    protected $bankTransferCost;

    public function __construct($deliverCost, $bankTransferCost)
    {
        $this->deliverCost = $deliverCost;
        $this->bankTransferCost = $bankTransferCost;
    }

    abstract protected function buildRedirectUrl($amount, $user);

    public function make($amount, $user)
    {
        $redirectUrl = $this->buildRedirectUrl($amount, $user);

        $totalAmount = $amount + $this->deliverCost + $this->bankTransferCost;
        $totalVatAmount = $totalAmount * 1.18;

        return [
            "totalAmount" => round($totalAmount, 2),
            "totalVatAmount" => round($totalVatAmount, 2),
            "redirectUrl" => $redirectUrl,
        ];
    }
}

class CreditCardPayment extends BasePaymentGateway
{
    protected function buildRedirectUrl($amount, $user)
    {
        return "https://partseurope.info/tesQng-creditcard/{$user->username}{$amount}";
    }
}

class PaypalPayment extends BasePaymentGateway
{
    protected function buildRedirectUrl($amount, $user)
    {
        return "https://partseurope.info/tesQng-paypal/{$user->email}/{$amount}";
    }
}

class BankTransferPayment extends BasePaymentGateway
{
    protected function buildRedirectUrl($amount, $user)
    {
        $hashed = md5($user->username);
        return "https://partseurope.info/tesQng-bank-transfer/{$hashed}/{$amount}";
    }
}

class CashOnDelivery extends BasePaymentGateway
{
    protected function buildRedirectUrl($amount, $user)
    {
        $hashed = substr(str_shuffle($user->userName), 0, 10);
        return "https://partseurope.info/tesQng-cod/{$hashed}/{$amount}";
    }
}


class User
{
    public $userName = "tapas";
    public $email = "testEmail@gmail.com";
}


//client code

$user = new User();

$payment  = new PaymentService(new CreditCardPayment(2, 0.40));

var_dump($payment->charge(30, $user));
