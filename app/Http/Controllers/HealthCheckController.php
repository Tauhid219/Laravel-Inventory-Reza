<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class HealthCheckController
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'app' => $this->checkApp(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
        ];

        $healthy = collect($checks)->every(fn (array $check): bool => $check['ok']);

        return response()->json([
            'status' => $healthy ? 'ok' : 'degraded',
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    /**
     * @return array{ok: bool}
     */
    private function checkApp(): array
    {
        return ['ok' => true];
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    private function checkDatabase(): array
    {
        try {
            DB::select('select 1');

            return ['ok' => true];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'ok' => false,
                'error' => 'database_unavailable',
            ];
        }
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    private function checkCache(): array
    {
        try {
            $key = 'health-check:'.sha1((string) microtime(true));

            Cache::put($key, 'ok', now()->addMinute());
            $ok = Cache::get($key) === 'ok';
            Cache::forget($key);

            return ['ok' => $ok];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'ok' => false,
                'error' => 'cache_unavailable',
            ];
        }
    }

    /**
     * @return array{ok: bool, error?: string}
     */
    private function checkStorage(): array
    {
        $paths = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($paths as $path) {
            if (! File::isDirectory($path) || ! File::isWritable($path)) {
                return [
                    'ok' => false,
                    'error' => 'storage_not_writable',
                ];
            }
        }

        return ['ok' => true];
    }
}
