<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use App\Providers\PayPalPaymentProvider;
use App\User\User;

class PayPalPaymentProviderTest extends TestCase
{
    private PayPalPaymentProvider $provider;
    private User $user;

    protected function setUp(): void
    {
        $this->provider = new PayPalPaymentProvider();
        $this->user = new User('testuser', 'test@example.com');
    }

    public function test_charge_calculates_correct_total_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 102.50;

        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_charge_calculates_correct_vat_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedVAT = round(102.50 * 1.18, 2);

        $this->assertEquals($expectedVAT, $result['totalVatAmount']);
    }

    public function test_charge_generates_correct_redirect_url(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedUrl = 'https://partseurope.info/testing-paypal/test%40example.com/100.00';

        $this->assertEquals($expectedUrl, $result['redirectUrl']);
    }

    public function test_charge_with_special_characters_in_email(): void
    {
        $userWithSpecialChars = new User('testuser', 'test+special@example.co.uk');
        $amount = 50.00;
        $result = $this->provider->charge($amount, $userWithSpecialChars);

        $expectedUrl = 'https://partseurope.info/testing-paypal/test%2Bspecial%40example.co.uk/50.00';

        $this->assertEquals($expectedUrl, $result['redirectUrl']);
    }

    public function test_charge_returns_all_required_fields(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $this->assertArrayHasKey('totalAmount', $result);
        $this->assertArrayHasKey('totalVatAmount', $result);
        $this->assertArrayHasKey('redirectUrl', $result);
    }

    public function test_delivery_cost_is_correct(): void
    {
        $amount = 0.00;
        $result = $this->provider->charge($amount, $this->user);

        $this->assertEquals(2.50, $result['totalAmount']);
    }
}
