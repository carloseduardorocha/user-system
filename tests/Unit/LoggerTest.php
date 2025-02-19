<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

use App\Helpers\Logger;

use Illuminate\Support\Facades\Log;
use Exception;

class LoggerTest extends TestCase
{
    public function test_dispatch_logs_message_with_context(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('error')
            ->once()
            ->with('Test message', [
                'env' => 'testing',
                'key' => 'value',
            ]);

        Logger::dispatch('error', 'Test message', ['key' => 'value']);
    }

    public function test_emergency_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('emergency')
            ->once()
            ->with('Emergency message', [
                'env' => 'testing',
            ]);

        Logger::emergency('Emergency message', []);
    }

    public function test_alert_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('alert')
            ->once()
            ->with('Alert message', [
                'env' => 'testing',
            ]);

        Logger::alert('Alert message', []);
    }

    public function test_critical_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('critical')
            ->once()
            ->with('Critical message', [
                'env' => 'testing',
            ]);

        Logger::critical('Critical message', []);
    }

    public function test_error_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('error')
            ->once()
            ->with('Error message', [
                'env' => 'testing',
            ]);

        Logger::error('Error message', []);
    }

    public function test_warning_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('warning')
            ->once()
            ->with('Warning message', [
                'env' => 'testing',
            ]);

        Logger::warning('Warning message', []);
    }

    public function test_notice_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('notice')
            ->once()
            ->with('Notice message', [
                'env' => 'testing',
            ]);

        Logger::notice('Notice message', []);
    }

    public function test_info_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('Info message', [
                'env' => 'testing',
            ]);

        Logger::info('Info message', []);
    }

    public function test_debug_logs_message(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('debug')
            ->once()
            ->with('Debug message', [
                'env' => 'testing',
            ]);

        Logger::debug('Debug message', []);
    }

    public function test_throw_exception_and_log(): void
    {
        Log::shouldReceive('channel')
            ->once()
            ->with('stack')
            ->andReturnSelf();

        Log::shouldReceive('error')
            ->once()
            ->with('Test exception message', [
                'env' => 'testing',
            ]);

        $this->expectException(Exception::class);

        Logger::throwExceptionAndLog('Test exception message');
    }
}
