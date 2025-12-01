<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use App\Providers\BankTransferPaymentProvider;
use App\User\User;

class BankTransferPaymentProviderTest extends TestCase
{
    private BankTransferPaymentProvider $provider;
    private User $user;

    protected function setUp(): void
    {
        $this->provider = new BankTransferPaymentProvider();
        $this->user = new User('testuser', 'test@example.com');
    }

    public function test_charge_calculates_correct_total_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 101.40;

        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_charge_calculates_correct_vat_amount(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedVAT = round(101.40 * 1.18, 2);

        $this->assertEquals($expectedVAT, $result['totalVatAmount']);
    }

    public function test_charge_generates_correct_redirect_url_with_md5_hash(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedHash = md5('testuser'); // MD5 hash of username
        $expectedUrl = "https://partseurope.info/testing-bank-transfer/{$expectedHash}/100.00";

        $this->assertEquals($expectedUrl, $result['redirectUrl']);
    }

    public function test_md5_hash_is_consistent(): void
    {
        $amount = 50.00;

        $result1 = $this->provider->charge($amount, $this->user);
        $result2 = $this->provider->charge($amount, $this->user);

        $this->assertEquals($result1['redirectUrl'], $result2['redirectUrl']);
    }

    public function test_charge_returns_all_required_fields(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $this->assertArrayHasKey('totalAmount', $result);
        $this->assertArrayHasKey('totalVatAmount', $result);
        $this->assertArrayHasKey('redirectUrl', $result);
    }

    public function test_free_delivery(): void
    {
        $amount = 100.00;
        $result = $this->provider->charge($amount, $this->user);

        $expectedTotal = 100.00 + 1.40; // No delivery cost
        $this->assertEquals($expectedTotal, $result['totalAmount']);
    }

    public function test_different_usernames_generate_different_hashes(): void
    {
        $user1 = new User('user1', 'test@example.com');
        $user2 = new User('user2', 'test@example.com');

        $result1 = $this->provider->charge(100.00, $user1);
        $result2 = $this->provider->charge(100.00, $user2);

        $this->assertNotEquals($result1['redirectUrl'], $result2['redirectUrl']);
    }
}
