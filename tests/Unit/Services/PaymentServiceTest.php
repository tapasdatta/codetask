<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\PaymentService;
use App\Providers\PaymentProviderInterface;
use App\User\User;

class PaymentServiceTest extends TestCase
{
    public function test_execute_calls_provider_charge_method(): void
    {
        $mockProvider = $this->createMock(PaymentProviderInterface::class);
        $user = new User('testuser', 'test@example.com');
        $amount = 100.00;

        $expectedResult = [
            'totalAmount' => 102.40,
            'totalVatAmount' => 120.83,
            'redirectUrl' => 'https://example.com/redirect'
        ];

        $mockProvider->expects($this->once())
            ->method('charge')
            ->with($amount, $user)
            ->willReturn($expectedResult);

        $paymentService = new PaymentService($mockProvider, $amount, $user);
        $result = $paymentService->execute();

        $this->assertEquals($expectedResult, $result);
    }

    public function test_execute_passes_correct_parameters(): void
    {
        $mockProvider = $this->createMock(PaymentProviderInterface::class);
        $user = new User('john_doe', 'john@example.com');
        $amount = 250.75;

        $mockProvider->expects($this->once())
            ->method('charge')
            ->with(
                $this->equalTo($amount),
                $this->equalTo($user)
            );

        $paymentService = new PaymentService($mockProvider, $amount, $user);
        $paymentService->execute();
    }

    public function test_constructor_sets_properties_indirectly(): void
    {
        $mockProvider = $this->createMock(PaymentProviderInterface::class);
        $user = new User('testuser', 'test@example.com');
        $amount = 100.00;

        $expectedResult = [
            'totalAmount' => 102.40,
            'totalVatAmount' => 120.83,
            'redirectUrl' => 'https://example.com/redirect'
        ];

        $mockProvider->expects($this->once())
            ->method('charge')
            ->with($amount, $user)
            ->willReturn($expectedResult);

        $paymentService = new PaymentService($mockProvider, $amount, $user);
        $result = $paymentService->execute();

        $this->assertEquals($expectedResult, $result);
    }
}
