<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
        ]);

        $middleware->prependToGroup('web', \App\Http\Middleware\SecurityHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Sentry: send errors to sentry.io dashboard + email alerts
        Integration::handles($exceptions);

        // Custom: save errors to local DB for admin panel with smart deduplication
        $exceptions->report(function (\Throwable $e) {
            try {
                $request = request();
                $message = mb_substr($e->getMessage() ?: get_class($e), 0, 1000);
                $file = $e->getFile();
                $line = $e->getLine();
                $fingerprint = \App\Models\ErrorLog::generateFingerprint($message, $file, $line);

                // Check if this exact error already exists
                $existing = \App\Models\ErrorLog::where('fingerprint', $fingerprint)->first();

                if ($existing) {
                    // Update existing: increment count, update last_seen_at, re-open if resolved
                    $existing->increment('occurrence_count');
                    $existing->update([
                        'last_seen_at' => now(),
                        'is_resolved' => false, // Re-open if error recurs
                    ]);
                } else {
                    // Create new error entry
                    \App\Models\ErrorLog::create([
                        'level' => match (true) {
                            $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 'warning',
                            $e instanceof \Illuminate\Validation\ValidationException => 'notice',
                            $e instanceof \Illuminate\Auth\AuthenticationException => 'notice',
                            default => 'error',
                        },
                        'fingerprint' => $fingerprint,
                        'occurrence_count' => 1,
                        'last_seen_at' => now(),
                        'message' => $message,
                        'file' => $file,
                        'line' => $line,
                        'url' => $request?->fullUrl(),
                        'method' => $request?->method(),
                        'user_id' => auth()->id(),
                        'ip' => $request?->ip(),
                        'user_agent' => mb_substr((string) $request?->userAgent(), 0, 500),
                        'trace' => mb_substr($e->getTraceAsString(), 0, 5000),
                        'context' => [
                            'exception' => get_class($e),
                            'code' => $e->getCode(),
                        ],
                    ]);
                }
            } catch (\Throwable $logError) {
                Log::warning('ErrorLog DB write failed: ' . $logError->getMessage());
            }

            return false; // continue to default Laravel logging
        });
    })->create();
