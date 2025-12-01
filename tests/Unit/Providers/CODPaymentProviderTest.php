<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use App\Providers\CODPaymentProvider;
use App\User\User;

class CODPaymentProviderTest extends TestCase
{
    private CODPaymentProvider $provider;
    private User $user;

    protected function setUp(): void
    {
        $this->provider = new CODPaymentProvider();
        $this->user = new User('testuser123', 'test@example.com');
    }

    public function test_charge_calculates_correct_total_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 105.50;

        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_charge_calculates_correct_vat_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedVAT = round(105.50 * 1.18, 2);

        $this->assertEquals($expectedVAT, $result['totalVatAmount']);
    }

    public function test_charge_generates_correct_redirect_url_format(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $this->assertStringStartsWith('https://partseurope.info/testing-cod/', $result['redirectUrl']);
        $this->assertStringEndsWith('/100.00', $result['redirectUrl']);

        $urlParts = explode('/', $result['redirectUrl']);
        $hash = $urlParts[4];

        $this->assertEquals(10, strlen($hash));
    }

    public function test_hash_string_length(): void
    {
        $amount = 50.00;
        $result = $this->provider->charge($amount, $this->user);

        // Extract hash from URL
        preg_match('/testing-cod\/([^\/]+)\//', $result['redirectUrl'], $matches);
        $hash = $matches[1];

        $this->assertEquals(10, strlen($hash));
    }

    public function test_hash_string_contains_only_valid_characters(): void
    {
        $amount = 75.00;
        $result = $this->provider->charge($amount, $this->user);

        preg_match('/testing-cod\/([^\/]+)\//', $result['redirectUrl'], $matches);
        $hash = $matches[1];

        $usernameChars = str_split($this->user->getUsername());
        $hashChars = str_split($hash);

        foreach ($hashChars as $char) {
            $this->assertContains($char, $usernameChars, "Hash contains character '{$char}' not in username");
        }
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

        $this->assertEquals(5.50, $result['totalAmount']);
    }

    public function test_no_bank_transfer_cost(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 100.00 + 5.50;
        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_hash_generation_with_short_username(): void
    {
        $shortUser = new User('ab', 'test@example.com');
        $amount = 100.00;
        $result = $this->provider->charge($amount, $shortUser);

        preg_match('/testing-cod\/([^\/]+)\//', $result['redirectUrl'], $matches);
        $hash = $matches[1];

        $this->assertGreaterThan(0, strlen($hash));
        $this->assertLessThanOrEqual(10, strlen($hash));
    }
}
