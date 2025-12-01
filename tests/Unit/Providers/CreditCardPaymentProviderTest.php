<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use App\Providers\CreditCardPaymentProvider;
use App\User\User;

class CreditCardPaymentProviderTest extends TestCase
{
    private CreditCardPaymentProvider $provider;
    private User $user;

    protected function setUp(): void
    {
        $this->provider = new CreditCardPaymentProvider();
        $this->user = new User('testuser', 'test@example.com');
    }

    public function test_charge_calculates_correct_total_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 102.40;

        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_charge_calculates_correct_vat_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedVAT = round(102.40 * 1.18, 2);

        $this->assertEquals($expectedVAT, $result['totalVatAmount']);
    }

    public function test_charge_generates_correct_redirect_url(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedUrl = 'https://partseurope.info/testing-creditcard/testuser/100.00';

        $this->assertEquals($expectedUrl, $result['redirectUrl']);
    }

    public function test_charge_with_special_characters_in_username(): void
    {
        $userWithSpecialChars = new User('test_user@123', 'test@example.com');
        $amount = 50.00;
        $result = $this->provider->charge($amount, $userWithSpecialChars);

        $expectedUrl = 'https://partseurope.info/testing-creditcard/test_user%40123/50.00';

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
}
