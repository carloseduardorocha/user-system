<?php

namespace App\Helpers;

use Throwable;
use Exception;

use Illuminate\Support\Facades\Log;

/**
 * Log Levels
 *
 * Numerical | Severity
 * Code      |
 * ----------------------------------------------------------
 * 0         | Emergency: system is unusable
 * 1         | Alert: action must be taken immediately
 * 2         | Critical: critical conditions
 * 3         | Error: error conditions
 * 4         | Warning: warning conditions
 * 5         | Notice: normal but significant condition
 * 6         | Informational: informational messages
 * 7         | Debug: debug-level messages
 *
 * References:
 *
 *    - https://laravel.com/docs/9.x/logging#log-levels
 *    - https://www.rfc-editor.org/rfc/rfc5424#page-11
 */
class Logger
{
    /**
     * Dispatch logs.
     *
     * @param string $level
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @param string|null $channel
     * @return void
     */
    public static function dispatch(string $level, string $message, array $context = [], array $tags = [], ?Throwable $e = null, ?string $channel = 'stack'): void
    {
        $stack_trace = [];

        if (!empty($e))
        {
            $context = array_merge(
                [
                    'Message' => $e->getMessage(),
                    'File'    => $e->getFile() . ':' . $e->getLine(),
                ],
                $context
            );

            $stack_trace = ['Stack Trace' => '<small style="font-size:10px;">' . nl2br($e->getTraceAsString()) . '</small>'];
        }

        // add env to context
        $context = array_merge(['env' => config('app.env')], $context);

        // add tags to context
        if (!empty($tags))
        {
            $context = array_merge($context, ['tags' => $tags]);
        }

        // add stack trace to context
        if (!empty($stack_trace))
        {
            $context = array_merge($context, $stack_trace);
        }

        Log::channel($channel)->{$level}($message, $context);
    }

    /**
     * Send logs with emergency level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function emergency(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('emergency', $message, $context, $tags, $e);
    }

    /**
     * Send logs with alert level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function alert(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('alert', $message, $context, $tags, $e);
    }

    /**
     * Send logs with critical level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function critical(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('critical', $message, $context, $tags, $e);
    }

    /**
     * Send logs with error level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function error(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('error', $message, $context, $tags, $e);
    }

    /**
     * Send logs with warning level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function warning(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('warning', $message, $context, $tags, $e);
    }

    /**
     * Send logs with notice level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function notice(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('notice', $message, $context, $tags, $e);
    }

    /**
     * Send logs with info level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function info(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('info', $message, $context, $tags, $e);
    }

    /**
     * Send logs with debug level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @param Throwable|null $e
     * @return void
     */
    public static function debug(string $message, array $context = [], array $tags = [], ?Throwable $e = null): void
    {
        self::dispatch('debug', $message, $context, $tags, $e);
    }

    /**
     * Throw exception and send log with error level.
     *
     * @param string $message
     * @param array<mixed> $context
     * @param array<string> $tags
     * @return Exception
     */
    public static function throwExceptionAndLog(string $message, array $context = [], array $tags = []): Exception
    {
        self::dispatch('error', $message, $context, $tags);

        throw new Exception($message);
    }
}
