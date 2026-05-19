<?php

namespace Tobuli\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HibpChecker
{
    private const ENDPOINT = 'https://api.pwnedpasswords.com/range/';
    private const TIMEOUT_SECONDS = 2;

    public static function isCompromised(string $password): bool
    {
        $sha1 = strtoupper(sha1($password));
        $prefix = substr($sha1, 0, 5);
        $suffix = substr($sha1, 5);

        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->withHeaders(['Add-Padding' => 'true'])
                ->get(self::ENDPOINT . $prefix);
        } catch (\Throwable $e) {
            Log::warning('HIBP check failed (network), letting password through', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }

        if (!$response->successful()) {
            Log::warning('HIBP check failed (http status), letting password through', [
                'status' => $response->status(),
            ]);
            return false;
        }

        foreach (preg_split('/\r?\n/', $response->body()) as $line) {
            $parts = explode(':', $line);
            if (count($parts) !== 2) {
                continue;
            }
            if (strtoupper(trim($parts[0])) === $suffix && (int) trim($parts[1]) > 0) {
                return true;
            }
        }

        return false;
    }
}
